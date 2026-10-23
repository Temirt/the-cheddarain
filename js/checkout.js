/**
 * The Cheddarloin — Checkout & Order Dispatch Module
 * Integrates Formspree for order intake and Netlify Function for Telegram dispatch.
 */

// Formspree Order Intake Endpoint
const FORMSPREE_ORDER_ENDPOINT = window.FORMSPREE_ENDPOINT || "https://formspree.io/f/xkjjwddv";

// Local storage key for customer's local order history (Temporary browser view, NOT a database)
const ORDER_HISTORY_KEY = "cheddarloin_recent_orders";

const CheddarCheckout = (function () {
    let isSubmitting = false;

    /**
     * Generate unique Order Reference in format CHD-XXXXXXXX (e.g. CHD-849201)
     * @returns {string}
     */
    function generateOrderReference() {
        const chars = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ';
        let randomPart = '';
        for (let i = 0; i < 6; i++) {
            randomPart += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        return `CHD-${randomPart}`;
    }

    /**
     * Save successful order to browser localStorage for "My Orders" view
     * @param {Object} orderData 
     */
    function recordLocalOrder(orderData) {
        try {
            const raw = localStorage.getItem(ORDER_HISTORY_KEY);
            const history = raw ? JSON.parse(raw) : [];
            history.unshift({
                reference: orderData.orderReference,
                date: new Date().toISOString(),
                customerName: orderData.customerName,
                phone: orderData.phone,
                orderType: orderData.orderType,
                deliveryAddress: orderData.deliveryAddress,
                total: orderData.total,
                items: orderData.items,
                telegramSent: orderData.telegramSent
            });
            // Keep up to 10 recent orders in browser memory
            localStorage.setItem(ORDER_HISTORY_KEY, JSON.stringify(history.slice(0, 10)));
        } catch (e) {
            console.error('Failed to save order to localStorage history:', e);
        }
    }

    /**
     * Read local order history from localStorage
     * @returns {Array}
     */
    function getLocalOrderHistory() {
        try {
            const raw = localStorage.getItem(ORDER_HISTORY_KEY);
            return raw ? JSON.parse(raw) : [];
        } catch (e) {
            return [];
        }
    }

    /**
     * Validate customer form fields
     * @param {Object} formData 
     * @returns {Object} { isValid: boolean, errors: Object }
     */
    function validateForm(formData) {
        const errors = {};

        if (!formData.customerName || formData.customerName.trim().length < 2) {
            errors.customerName = "Please enter your full name (minimum 2 characters).";
        }

        // Validate phone number: minimum 9 digits
        const cleanPhone = (formData.phone || '').replace(/[^0-9+]/g, '');
        if (!cleanPhone || cleanPhone.length < 9) {
            errors.phone = "Please enter a valid phone number (e.g. 0911234567 or +251...).";
        }

        if (formData.orderType === 'delivery') {
            if (!formData.deliveryAddress || formData.deliveryAddress.trim().length < 5) {
                errors.deliveryAddress = "Please provide your specific delivery address or landmark.";
            }
        }

        const items = CheddarCart.getItems();
        if (!items || items.length === 0) {
            errors.cart = "Your shopping cart is empty. Please add items before checking out.";
        }

        return {
            isValid: Object.keys(errors).length === 0,
            errors
        };
    }

    /**
     * Submit order to Formspree and Telegram Function
     * @param {Object} formData 
     * @param {Function} callbacks { onStart, onSuccess, onError, onPartialSuccess }
     */
    async function submitOrder(formData, callbacks = {}) {
        if (isSubmitting) return;

        const validation = validateForm(formData);
        if (!validation.isValid) {
            if (callbacks.onError) callbacks.onError(validation.errors);
            return;
        }

        const items = CheddarCart.getItems();
        const subtotal = CheddarCart.getSubtotal();
        const orderType = formData.orderType || 'delivery';
        const deliveryFee = CheddarCart.getDeliveryFee(orderType);
        const total = CheddarCart.getTotal(orderType);
        const orderReference = generateOrderReference();

        // Prepare clean structured order payload
        const orderPayload = {
            orderReference: orderReference,
            customerName: formData.customerName.trim(),
            phone: formData.phone.trim(),
            orderType: orderType,
            deliveryAddress: orderType === 'delivery' ? formData.deliveryAddress.trim() : 'In-store Pickup',
            instructions: (formData.instructions || '').trim(),
            items: items.map(i => ({
                productId: i.productId,
                name: i.name,
                quantity: i.quantity,
                unitPrice: i.price,
                subtotal: i.price * i.quantity
            })),
            subtotal: subtotal,
            deliveryFee: deliveryFee,
            total: total,
            createdAt: new Date().toISOString()
        };

        // Formspree payload formatted for tabular inspection in Formspree dashboard
        const formspreePayload = {
            _subject: `New Order #${orderReference} — The Cheddarloin (ETB ${total.toLocaleString()})`,
            orderReference: orderReference,
            customerName: orderPayload.customerName,
            phoneNumber: orderPayload.phone,
            orderType: orderPayload.orderType.toUpperCase(),
            deliveryAddress: orderPayload.deliveryAddress,
            specialInstructions: orderPayload.instructions || 'None',
            itemsList: orderPayload.items.map(i => `${i.quantity}x ${i.name} (ETB ${i.subtotal})`).join(', '),
            calculatedSubtotalETB: subtotal,
            deliveryFeeETB: deliveryFee,
            grandTotalETB: total
        };

        isSubmitting = true;
        if (callbacks.onStart) callbacks.onStart();

        let formspreeSuccess = false;
        let isPlaceholderEndpoint = FORMSPREE_ORDER_ENDPOINT.includes('YOUR_FORM_ID');

        // STEP 1: Process order intake through Formspree
        try {
            if (isPlaceholderEndpoint) {
                // When in initial development before user sets their Formspree ID,
                // simulate successful intake so order flow and Telegram testing can proceed smoothly
                console.info('[Formspree] Running with placeholder form ID. Simulating order intake.');
                formspreeSuccess = true;
            } else {
                const formspreeResp = await fetch(FORMSPREE_ORDER_ENDPOINT, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(formspreePayload)
                });

                if (formspreeResp.ok) {
                    formspreeSuccess = true;
                } else {
                    const errData = await formspreeResp.json().catch(() => ({}));
                    console.error('[Formspree] Submission rejected:', errData);
                    throw new Error(errData.error || 'Formspree submission was rejected by server.');
                }
            }
        } catch (formspreeError) {
            console.error('[Checkout] Formspree intake failed:', formspreeError);
            isSubmitting = false;
            // CRITICAL: DO NOT clear cart if Formspree submission failed!
            if (callbacks.onError) {
                callbacks.onError({
                    general: `We could not process your order at this time (${formspreeError.message || 'Network error'}). Your cart has been preserved. Please check your connection and try again.`
                });
            }
            return;
        }

        // STEP 2: Dispatch order notification to Telegram via Netlify Serverless Function
        let telegramSent = false;
        let telegramMessage = '';

        try {
            const telegramResp = await fetch('/.netlify/functions/telegram-order', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(orderPayload)
            });

            if (telegramResp.ok) {
                const telegramResult = await telegramResp.json();
                telegramSent = Boolean(telegramResult.telegramSent);
                if (telegramResult.reason) {
                    telegramMessage = telegramResult.reason;
                }
            } else {
                const errData = await telegramResp.json().catch(() => ({}));
                console.warn('[Telegram Function] Serverless function response:', errData);
                telegramMessage = errData.error || 'Telegram notification could not be delivered.';
            }
        } catch (telegramErr) {
            console.warn('[Telegram Function] Could not reach Netlify function (likely local development preview):', telegramErr);
            telegramMessage = 'Serverless Telegram endpoint unreachable in static preview.';
        }

        // Save order to customer's browser order history
        recordLocalOrder({
            ...orderPayload,
            telegramSent: telegramSent
        });

        // Safe to clear cart now that Formspree order intake succeeded
        CheddarCart.clear();
        isSubmitting = false;

        // Callback with full outcome
        if (telegramSent) {
            if (callbacks.onSuccess) {
                callbacks.onSuccess({
                    orderReference,
                    total,
                    orderType,
                    customerName: orderPayload.customerName,
                    phone: orderPayload.phone,
                    telegramSent: true,
                    isPlaceholderEndpoint
                });
            }
        } else {
            // Formspree succeeded, but Telegram was not configured or skipped
            if (callbacks.onPartialSuccess) {
                callbacks.onPartialSuccess({
                    orderReference,
                    total,
                    orderType,
                    customerName: orderPayload.customerName,
                    phone: orderPayload.phone,
                    telegramSent: false,
                    note: telegramMessage,
                    isPlaceholderEndpoint
                });
            }
        }
    }

    return {
        submitOrder,
        validateForm,
        getLocalOrderHistory,
        generateOrderReference
    };
})();
