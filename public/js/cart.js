/**
 * The Cheddarloin — Vanilla JavaScript Shopping Cart Engine
 * Persistent client-side cart with option customization & price calculation
 */

const Cart = {
    storageKey: 'cheddarloin_cart',

    getItems() {
        try {
            let data = localStorage.getItem(this.storageKey);
            if (!data) {
                // Fallback for previous session cart
                data = localStorage.getItem('cheddarain_cart');
            }
            return data ? JSON.parse(data) : [];
        } catch (e) {
            console.error('Error reading cart from localStorage', e);
            return [];
        }
    },

    saveItems(items) {
        try {
            localStorage.setItem(this.storageKey, JSON.stringify(items));
            this.updateBadges();
            window.dispatchEvent(new CustomEvent('cart:updated', { detail: { items } }));
        } catch (e) {
            console.error('Error saving cart to localStorage', e);
        }
    },

    addItem(product, quantity = 1, options = []) {
        const items = this.getItems();
        
        // Generate a unique fingerprint based on product id + options
        const optionsSignature = options.map(o => `${o.option_name}:${o.value_id}`).sort().join('|');
        const itemIndex = items.findIndex(i => i.product_id === product.id && i.options_signature === optionsSignature);

        const optionsTotal = options.reduce((sum, opt) => sum + (parseFloat(opt.price) || 0), 0);
        const unitPrice = parseFloat(product.price) + optionsTotal;

        if (itemIndex > -1) {
            items[itemIndex].quantity += quantity;
            items[itemIndex].subtotal = items[itemIndex].quantity * unitPrice;
        } else {
            items.push({
                product_id: product.id,
                name: product.name,
                image_url: product.image_url,
                base_price: parseFloat(product.price),
                unit_price: unitPrice,
                quantity: quantity,
                subtotal: unitPrice * quantity,
                options: options,
                options_signature: optionsSignature
            });
        }

        this.saveItems(items);
        this.showToast(`Added ${quantity}x ${product.name} to cart!`);
    },

    updateQuantity(product_id, options_signature, newQty) {
        let items = this.getItems();
        if (newQty <= 0) {
            items = items.filter(i => !(i.product_id === product_id && i.options_signature === options_signature));
        } else {
            const item = items.find(i => i.product_id === product_id && i.options_signature === options_signature);
            if (item) {
                item.quantity = newQty;
                item.subtotal = item.quantity * item.unit_price;
            }
        }
        this.saveItems(items);
    },

    removeItem(product_id, options_signature) {
        const items = this.getItems().filter(i => !(i.product_id === product_id && i.options_signature === options_signature));
        this.saveItems(items);
        this.showToast('Item removed from cart.');
    },

    clear() {
        localStorage.removeItem(this.storageKey);
        this.updateBadges();
        window.dispatchEvent(new CustomEvent('cart:cleared'));
    },

    getCount() {
        return this.getItems().reduce((total, item) => total + item.quantity, 0);
    },

    getSubtotal() {
        return this.getItems().reduce((sum, item) => sum + (parseFloat(item.subtotal) || 0), 0);
    },

    updateBadges() {
        const count = this.getCount();
        document.querySelectorAll('.cart-count-badge').forEach(badge => {
            badge.textContent = count;
            badge.style.display = count > 0 ? 'flex' : 'none';
        });
    },

    showToast(message) {
        let toast = document.getElementById('site-toast');
        if (!toast) {
            toast = document.createElement('div');
            toast.id = 'site-toast';
            toast.style.cssText = `
                position: fixed;
                bottom: 25px;
                right: 25px;
                background: linear-gradient(135deg, #ff9900 0%, #e85d04 100%);
                color: #000;
                font-weight: 700;
                font-family: var(--font-heading);
                padding: 1rem 1.6rem;
                border-radius: 9999px;
                box-shadow: 0 8px 25px rgba(255, 153, 0, 0.4);
                z-index: 9999;
                transform: translateY(100px);
                opacity: 0;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                pointer-events: none;
            `;
            document.body.appendChild(toast);
        }

        toast.textContent = message;
        toast.style.transform = 'translateY(0)';
        toast.style.opacity = '1';

        clearTimeout(this._toastTimeout);
        this._toastTimeout = setTimeout(() => {
            toast.style.transform = 'translateY(100px)';
            toast.style.opacity = '0';
        }, 3200);
    }
};

// Auto initialize badges on load
document.addEventListener('DOMContentLoaded', () => {
    Cart.updateBadges();
});
