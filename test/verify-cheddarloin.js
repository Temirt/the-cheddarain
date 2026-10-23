/**
 * Automated Verification Script for The Cheddarloin
 * Tests Netlify serverless function, price recalculation, validation, product catalog, and security.
 */

const assert = require('assert');
const { PRODUCTS, getProductById, formatETB } = require('../js/products');
const telegramFunction = require('../netlify/functions/telegram-order');

console.log('🧪 Starting The Cheddarloin Automated Test Suite...\n');

let passedTests = 0;
let totalTests = 0;

function it(description, fn) {
    totalTests++;
    try {
        fn();
        console.log(`  ✓ ${description}`);
        passedTests++;
    } catch (err) {
        console.error(`  ✕ ${description}`);
        console.error(`    Error: ${err.message}`);
    }
}

async function itAsync(description, fn) {
    totalTests++;
    try {
        await fn();
        console.log(`  ✓ ${description}`);
        passedTests++;
    } catch (err) {
        console.error(`  ✕ ${description}`);
        console.error(`    Error: ${err.message}`);
    }
}

(async () => {
    // -------------------------------------------------------------------------
    // TEST SUITE 1: Product Catalog & Currency
    // -------------------------------------------------------------------------
    console.log('📦 1. Product Catalog & Ethiopian Birr (ETB) Tests:');

    it('Product catalog should contain at least 15 items across all 4 categories', () => {
        assert(Array.isArray(PRODUCTS), 'PRODUCTS must be an array');
        assert(PRODUCTS.length >= 15, `Expected at least 15 products, found ${PRODUCTS.length}`);

        const categories = new Set(PRODUCTS.map(p => p.category));
        assert(categories.has('burgers'), 'Missing burgers category');
        assert(categories.has('sides'), 'Missing sides category');
        assert(categories.has('drinks'), 'Missing drinks category');
        assert(categories.has('meals'), 'Missing meals category');
    });

    it('Every product has valid price in ETB, image URL, name, and prep time', () => {
        PRODUCTS.forEach(p => {
            assert(p.id && typeof p.id === 'string', `Invalid product id: ${JSON.stringify(p)}`);
            assert(p.name && typeof p.name === 'string', `Invalid name for ${p.id}`);
            assert(typeof p.price === 'number' && p.price > 0, `Invalid price for ${p.id}: ${p.price}`);
            assert(p.image && p.image.startsWith('https://'), `Invalid image URL for ${p.id}`);
            assert(p.prepTime, `Missing prepTime for ${p.id}`);
        });
    });

    it('formatETB helper formats currency accurately', () => {
        assert.strictEqual(formatETB(650), 'ETB 650');
        assert.strictEqual(formatETB(1650), 'ETB 1,650');
        assert.strictEqual(formatETB(0), 'ETB 0');
    });

    it('getProductById retrieves correct product record', () => {
        const burger = getProductById('burger-cheddar-royal');
        assert(burger !== null, 'burger-cheddar-royal should exist');
        assert.strictEqual(burger.price, 650);
        assert.strictEqual(burger.category, 'burgers');
    });

    // -------------------------------------------------------------------------
    // TEST SUITE 2: Netlify Serverless Function (telegram-order.js)
    // -------------------------------------------------------------------------
    console.log('\n⚡ 2. Netlify Serverless Function Security & Validation Tests:');

    await itAsync('Rejects non-POST HTTP methods with 405 Method Not Allowed', async () => {
        const event = { httpMethod: 'GET' };
        const res = await telegramFunction.handler(event, {});
        assert.strictEqual(res.statusCode, 405);
        const body = JSON.parse(res.body);
        assert.strictEqual(body.success, false);
    });

    await itAsync('Rejects empty or malformed JSON payloads with 400', async () => {
        const event = { httpMethod: 'POST', body: 'not-valid-json' };
        const res = await telegramFunction.handler(event, {});
        assert.strictEqual(res.statusCode, 400);
    });

    await itAsync('Validates required fields: missing phone or name returns 400', async () => {
        const event = {
            httpMethod: 'POST',
            body: JSON.stringify({
                orderReference: 'CHD-123456',
                customerName: '', // empty name
                phone: '0911234567',
                orderType: 'pickup',
                items: [{ productId: 'burger-cheddar-royal', quantity: 1 }]
            })
        };
        const res = await telegramFunction.handler(event, {});
        assert.strictEqual(res.statusCode, 400);
        const body = JSON.parse(res.body);
        assert(body.error.includes('name'));
    });

    await itAsync('Requires delivery address when orderType is Delivery', async () => {
        const event = {
            httpMethod: 'POST',
            body: JSON.stringify({
                orderReference: 'CHD-123456',
                customerName: 'Abebe Bikila',
                phone: '0911234567',
                orderType: 'delivery',
                deliveryAddress: '', // missing
                items: [{ productId: 'burger-cheddar-royal', quantity: 1 }]
            })
        };
        const res = await telegramFunction.handler(event, {});
        assert.strictEqual(res.statusCode, 400);
        const body = JSON.parse(res.body);
        assert(body.error.includes('address'));
    });

    await itAsync('Rejects unknown/fake product IDs with 400 Bad Request', async () => {
        const event = {
            httpMethod: 'POST',
            body: JSON.stringify({
                orderReference: 'CHD-123456',
                customerName: 'Abebe Bikila',
                phone: '0911234567',
                orderType: 'pickup',
                items: [{ productId: 'fake-hacker-burger', quantity: 1 }]
            })
        };
        const res = await telegramFunction.handler(event, {});
        assert.strictEqual(res.statusCode, 400);
        const body = JSON.parse(res.body);
        assert(body.error.includes('Unrecognized product'));
    });

    await itAsync('Recalculates prices on server independently (prevents frontend tampering)', async () => {
        // Tampered client claim: trying to claim ETB 1 total instead of actual price (650 * 2 + 240 + 100 delivery = 1640)
        const event = {
            httpMethod: 'POST',
            body: JSON.stringify({
                orderReference: 'CHD-782901',
                customerName: 'Abebe Bikila',
                phone: '0911234567',
                orderType: 'delivery',
                deliveryAddress: 'Bole Atlas, near Edna Mall',
                instructions: '<script>alert("hack")</script>',
                items: [
                    { productId: 'burger-cheddar-royal', quantity: 2, clientClaimedPrice: 1 },
                    { productId: 'side-loaded-fries', quantity: 1, clientClaimedPrice: 1 }
                ],
                clientClaimedTotal: 3 // Tampered
            })
        };
        const res = await telegramFunction.handler(event, {});
        assert.strictEqual(res.statusCode, 200);
        const body = JSON.parse(res.body);
        assert.strictEqual(body.success, true);
        // Correct calculation: (650 * 2) + 240 + 100 = 1640 ETB
        assert.strictEqual(body.calculatedTotal, 1640, `Expected server total 1640, got ${body.calculatedTotal}`);
    });

    await itAsync('Handles missing Telegram environment variables gracefully without crashing', async () => {
        // Ensure env variables are unset
        delete process.env.TELEGRAM_BOT_TOKEN;
        delete process.env.TELEGRAM_CHAT_ID;

        const event = {
            httpMethod: 'POST',
            body: JSON.stringify({
                orderReference: 'CHD-998877',
                customerName: 'Sara Mengistu',
                phone: '0922334455',
                orderType: 'pickup',
                items: [{ productId: 'drink-salted-caramel-shake', quantity: 2 }]
            })
        };
        const res = await telegramFunction.handler(event, {});
        assert.strictEqual(res.statusCode, 200);
        const body = JSON.parse(res.body);
        assert.strictEqual(body.success, true);
        assert.strictEqual(body.telegramSent, false);
        assert(body.reason.includes('credentials not configured'));
        // 220 * 2 = 440 ETB
        assert.strictEqual(body.calculatedTotal, 440);
    });

    // -------------------------------------------------------------------------
    // Summary
    // -------------------------------------------------------------------------
    console.log(`\n==================================================`);
    console.log(`Results: ${passedTests} / ${totalTests} tests passed (${Math.round(passedTests/totalTests*100)}%)`);
    console.log(`==================================================`);

    if (passedTests === totalTests) {
        console.log('✅ ALL THE CHEDDARLOIN AUTOMATED TESTS PASSED CLEANLY.\n');
        process.exit(0);
    } else {
        console.error('❌ Some tests failed.\n');
        process.exit(1);
    }
})();
