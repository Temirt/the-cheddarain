/**
 * The Cheddarloin — Netlify Serverless Function: Telegram Order Dispatch
 * Path: netlify/functions/telegram-order.js
 * 
 * Securely receives validated orders, verifies products and calculates prices on the server,
 * escapes customer inputs, and sends structured notifications to the owner's Telegram chat.
 */

// Trusted server-side product catalog with authoritative prices (in ETB)
const SERVER_CATALOG = {
    "burger-cheddar-royal": { name: "The Cheddarloin Royal", price: 650 },
    "burger-smoked-bacon-melt": { name: "Smoked Bacon & Melted Cheddar", price: 620 },
    "burger-truffle-gold": { name: "Truffle Gold Reserve", price: 780 },
    "burger-fiery-jalapeno": { name: "Fiery Cheddar Jalapeño", price: 580 },
    "burger-classic-single": { name: "Classic Artisan Cheddar", price: 450 },
    "side-loaded-fries": { name: "Loaded Cheddar & Bacon Fries", price: 240 },
    "side-rustic-wedges": { name: "Rustic Golden Wedges", price: 180 },
    "side-cheddar-bites": { name: "Crispy Molten Cheddar Bites", price: 280 },
    "side-parmesan-truffle-fries": { name: "Truffle & Herb Shoestring Fries", price: 260 },
    "drink-salted-caramel-shake": { name: "Salted Caramel Velvet Milkshake", price: 220 },
    "drink-sparkling-hibiscus": { name: "Sparkling Hibiscus Lemon Cooler", price: 150 },
    "drink-mint-lime-sparkler": { name: "Fresh Mint & Lime Sparkler", price: 140 },
    "drink-craft-botanical-cola": { name: "Artisanal Botanical Cola", price: 120 },
    "meal-royal-combo": { name: "The Royal Cheddarloin Feast", price: 980 },
    "meal-bacon-smokehouse": { name: "Smokehouse Bacon Combo", price: 860 },
    "meal-duo-platter": { name: "Cheddar Lovers Duo (For 2)", price: 1650 }
};

const DELIVERY_FEE = 100; // Authoritative server delivery fee in ETB

/**
 * Escape HTML special characters for safe inclusion in Telegram HTML message mode
 * @param {string} str 
 * @returns {string}
 */
function escapeHtml(str) {
    if (!str || typeof str !== 'string') return '';
    return str
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;');
}

exports.handler = async (event, context) => {
    // 1. Only allow HTTP POST
    if (event.httpMethod !== 'POST') {
        return {
            statusCode: 405,
            headers: { 'Content-Type': 'application/json', 'Allow': 'POST' },
            body: JSON.stringify({ success: false, error: 'Method Not Allowed' })
        };
    }

    // 2. Limit request payload size (protect serverless memory)
    if (event.body && event.body.length > 50000) {
        return {
            statusCode: 413,
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ success: false, error: 'Payload Too Large' })
        };
    }

    let payload;
    try {
        payload = JSON.parse(event.body || '{}');
    } catch (parseError) {
        return {
            statusCode: 400,
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ success: false, error: 'Invalid JSON payload' })
        };
    }

    // 3. Extract and validate required customer fields
    const {
        orderReference,
        customerName,
        phone,
        orderType,
        deliveryAddress,
        instructions,
        items
    } = payload;

    if (!orderReference || typeof orderReference !== 'string') {
        return {
            statusCode: 400,
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ success: false, error: 'Missing or invalid orderReference' })
        };
    }

    if (!customerName || typeof customerName !== 'string' || customerName.trim().length === 0) {
        return {
            statusCode: 400,
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ success: false, error: 'Customer name is required' })
        };
    }

    if (!phone || typeof phone !== 'string' || phone.trim().length < 6) {
        return {
            statusCode: 400,
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ success: false, error: 'Valid phone number is required' })
        };
    }

    const normalizedOrderType = (orderType === 'pickup') ? 'Pickup' : 'Delivery';

    if (normalizedOrderType === 'Delivery' && (!deliveryAddress || typeof deliveryAddress !== 'string' || deliveryAddress.trim().length < 3)) {
        return {
            statusCode: 400,
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ success: false, error: 'Delivery address is required for Delivery orders' })
        };
    }

    if (!Array.isArray(items) || items.length === 0) {
        return {
            statusCode: 400,
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ success: false, error: 'Order must contain at least one product' })
        };
    }

    // 4. Server-Side Price & Quantity Recalculation (DO NOT TRUST CLIENT TOTALS)
    let calculatedSubtotal = 0;
    const validatedItems = [];

    for (const item of items) {
        const productId = item.productId || item.id;
        const catalogItem = SERVER_CATALOG[productId];

        if (!catalogItem) {
            return {
                statusCode: 400,
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ success: false, error: `Unrecognized product in order: ${productId}` })
            };
        }

        const quantity = parseInt(item.quantity, 10);
        if (isNaN(quantity) || quantity <= 0 || quantity > 50) {
            return {
                statusCode: 400,
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ success: false, error: `Invalid quantity for product ${catalogItem.name}` })
            };
        }

        const itemSubtotal = catalogItem.price * quantity;
        calculatedSubtotal += itemSubtotal;

        validatedItems.push({
            name: catalogItem.name,
            quantity: quantity,
            unitPrice: catalogItem.price,
            subtotal: itemSubtotal
        });
    }

    const calculatedDeliveryFee = (normalizedOrderType === 'Delivery') ? DELIVERY_FEE : 0;
    const calculatedGrandTotal = calculatedSubtotal + calculatedDeliveryFee;

    // 5. Build Formatted Telegram Message
    // Clean, readable, and escaped to prevent injection
    const safeRef = escapeHtml(orderReference.trim());
    const safeName = escapeHtml(customerName.trim());
    const safePhone = escapeHtml(phone.trim());
    const safeOrderType = escapeHtml(normalizedOrderType);
    const safeAddress = normalizedOrderType === 'Delivery' 
        ? escapeHtml(deliveryAddress.trim()) 
        : 'In-store Pickup (The Cheddarloin)';
    const safeInstructions = (instructions && instructions.trim().length > 0)
        ? escapeHtml(instructions.trim())
        : 'None';

    const itemsTextLines = validatedItems.map(i => {
        return `${i.quantity} x ${escapeHtml(i.name)} — ETB ${i.subtotal.toLocaleString()}`;
    }).join('\n');

    const telegramMessage = 
`<b>THE CHEDDARLOIN — NEW ORDER</b>

<b>Order Reference:</b> <code>${safeRef}</code>

<b>Customer:</b> ${safeName}
<b>Phone:</b> ${safePhone}

<b>Order Type:</b> ${safeOrderType}

<b>Address:</b> ${safeAddress}

<b>ITEMS:</b>
${itemsTextLines}

<b>TOTAL:</b> ETB ${calculatedGrandTotal.toLocaleString()}${calculatedDeliveryFee > 0 ? ` (Includes ETB ${calculatedDeliveryFee} delivery)` : ''}

<b>Instructions:</b> ${safeInstructions}

<i>Please contact the customer to confirm the order.</i>`;

    // 6. Inspect Telegram Bot Environment Variables
    const botToken = process.env.TELEGRAM_BOT_TOKEN;
    const chatId = process.env.TELEGRAM_CHAT_ID;

    // Graceful handling when credentials have not been configured yet
    if (!botToken || !chatId || botToken === 'YOUR_TELEGRAM_BOT_TOKEN' || chatId === 'YOUR_TELEGRAM_CHAT_ID') {
        console.warn('[Telegram Function] Bot credentials not yet set in environment. Skipping Telegram send.');
        return {
            statusCode: 200,
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                success: true,
                orderReference: safeRef,
                telegramSent: false,
                reason: 'Telegram bot credentials not configured in Netlify environment variables (TELEGRAM_BOT_TOKEN / TELEGRAM_CHAT_ID).',
                calculatedTotal: calculatedGrandTotal,
                itemCount: validatedItems.length
            })
        };
    }

    // 7. Dispatch message to Telegram Bot API
    try {
        const telegramUrl = `https://api.telegram.org/bot${botToken}/sendMessage`;
        const response = await fetch(telegramUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                chat_id: chatId,
                text: telegramMessage,
                parse_mode: 'HTML',
                disable_web_page_preview: true
            })
        });

        const telegramResponse = await response.json();

        if (!response.ok || !telegramResponse.ok) {
            console.error('[Telegram Function] Telegram API rejected message:', telegramResponse);
            return {
                statusCode: 502,
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    success: false,
                    orderReference: safeRef,
                    telegramSent: false,
                    error: 'Failed to deliver notification to Telegram chat. Please verify bot permissions.',
                    telegramErrorCode: telegramResponse.error_code || null
                })
            };
        }

        // Successfully sent to Telegram
        return {
            statusCode: 200,
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                success: true,
                orderReference: safeRef,
                telegramSent: true,
                calculatedTotal: calculatedGrandTotal,
                itemCount: validatedItems.length
            })
        };

    } catch (networkError) {
        console.error('[Telegram Function] Network error calling Telegram API:', networkError);
        return {
            statusCode: 500,
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                success: false,
                orderReference: safeRef,
                telegramSent: false,
                error: 'Internal error communicating with Telegram Bot API.'
            })
        };
    }
};
