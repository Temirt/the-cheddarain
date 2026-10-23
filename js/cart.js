/**
 * The Cheddarloin — Shopping Cart Module
 * Manages cart state in browser localStorage (temporary browser storage, NOT a database)
 */

const CheddarCart = (function () {
    const STORAGE_KEY = 'cheddarloin_cart_v1';
    const DELIVERY_FEE = 100; // ETB 100 for delivery

    /**
     * Read cart items from localStorage
     * @returns {Array} Array of cart items { productId, name, price, quantity, image }
     */
    function getItems() {
        try {
            const raw = localStorage.getItem(STORAGE_KEY);
            if (!raw) return [];
            const parsed = JSON.parse(raw);
            return Array.isArray(parsed) ? parsed : [];
        } catch (e) {
            console.error('Failed to parse cart from localStorage:', e);
            return [];
        }
    }

    /**
     * Save items array into localStorage and trigger update event
     * @param {Array} items 
     */
    function saveItems(items) {
        try {
            localStorage.setItem(STORAGE_KEY, JSON.stringify(items));
        } catch (e) {
            console.error('Failed to save cart to localStorage:', e);
        }
        notify();
    }

    /**
     * Add a product to the cart or increment quantity
     * @param {string} productId 
     * @param {number} qty 
     */
    function addItem(productId, qty = 1) {
        const product = getProductById(productId);
        if (!product) {
            console.error('Product not found:', productId);
            return false;
        }

        const quantityToAdd = Math.max(1, parseInt(qty, 10) || 1);
        const items = getItems();
        const existingIndex = items.findIndex(item => item.productId === productId);

        if (existingIndex > -1) {
            items[existingIndex].quantity += quantityToAdd;
        } else {
            items.push({
                productId: product.id,
                name: product.name,
                price: Number(product.price),
                quantity: quantityToAdd,
                image: product.image,
                category: product.category
            });
        }

        saveItems(items);
        return true;
    }

    /**
     * Update quantity of an item in the cart
     * @param {string} productId 
     * @param {number} newQty 
     */
    function updateQuantity(productId, newQty) {
        const qty = parseInt(newQty, 10);
        let items = getItems();

        if (qty <= 0) {
            items = items.filter(item => item.productId !== productId);
        } else {
            const target = items.find(item => item.productId === productId);
            if (target) {
                target.quantity = qty;
            }
        }

        saveItems(items);
    }

    /**
     * Remove an item completely from the cart
     * @param {string} productId 
     */
    function removeItem(productId) {
        let items = getItems();
        items = items.filter(item => item.productId !== productId);
        saveItems(items);
    }

    /**
     * Clear all cart contents from localStorage
     */
    function clear() {
        try {
            localStorage.removeItem(STORAGE_KEY);
        } catch (e) {
            console.error('Failed to clear cart:', e);
        }
        notify();
    }

    /**
     * Get total number of individual items
     * @returns {number}
     */
    function getCount() {
        return getItems().reduce((sum, item) => sum + (item.quantity || 0), 0);
    }

    /**
     * Calculate subtotal of all items
     * @returns {number}
     */
    function getSubtotal() {
        return getItems().reduce((sum, item) => sum + ((item.price || 0) * (item.quantity || 0)), 0);
    }

    /**
     * Calculate final total based on fulfillment type
     * @param {string} orderType 'delivery' or 'pickup'
     * @returns {number}
     */
    function getTotal(orderType = 'delivery') {
        const subtotal = getSubtotal();
        if (subtotal === 0) return 0;
        const fee = orderType === 'delivery' ? DELIVERY_FEE : 0;
        return subtotal + fee;
    }

    /**
     * Get the delivery fee in ETB
     * @param {string} orderType 
     * @returns {number}
     */
    function getDeliveryFee(orderType = 'delivery') {
        return orderType === 'delivery' ? DELIVERY_FEE : 0;
    }

    /**
     * Dispatch custom DOM event to synchronize all cart UI elements
     */
    function notify() {
        const event = new CustomEvent('cart:updated', {
            detail: {
                items: getItems(),
                count: getCount(),
                subtotal: getSubtotal()
            }
        });
        window.dispatchEvent(event);
    }

    // Public API
    return {
        getItems,
        addItem,
        updateQuantity,
        removeItem,
        clear,
        getCount,
        getSubtotal,
        getTotal,
        getDeliveryFee,
        DELIVERY_FEE
    };
})();
