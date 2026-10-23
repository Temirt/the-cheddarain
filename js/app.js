/**
 * The Cheddarloin — Main Application Script
 * Orchestrates UI interactions, category tabs, cart drawer, checkout modal, and order history.
 */

document.addEventListener('DOMContentLoaded', () => {
    // -------------------------------------------------------------------------
    // DOM Elements
    // -------------------------------------------------------------------------
    const menuGrid = document.getElementById('menu-grid');
    const categoryTabs = document.querySelectorAll('.category-tab');
    const searchInput = document.getElementById('menu-search-input');
    
    // Cart Elements
    const cartToggleBtns = document.querySelectorAll('.cart-toggle-trigger');
    const cartDrawer = document.getElementById('cart-drawer');
    const cartBackdrop = document.getElementById('cart-backdrop');
    const cartCloseBtn = document.getElementById('cart-close-btn');
    const cartItemsList = document.getElementById('cart-items-list');
    const cartEmptyState = document.getElementById('cart-empty-state');
    const cartSummaryContainer = document.getElementById('cart-summary-container');
    const cartCountBadges = document.querySelectorAll('.cart-count-badge');
    const cartSubtotalEl = document.getElementById('cart-subtotal-val');
    const cartDeliveryFeeEl = document.getElementById('cart-delivery-fee-val');
    const cartTotalEl = document.getElementById('cart-total-val');
    const proceedToCheckoutBtn = document.getElementById('proceed-to-checkout-btn');

    // Checkout Elements
    const checkoutModal = document.getElementById('checkout-modal');
    const checkoutCloseBtn = document.getElementById('checkout-close-btn');
    const checkoutForm = document.getElementById('checkout-form');
    const checkoutSubmitBtn = document.getElementById('checkout-submit-btn');
    const checkoutItemsSummary = document.getElementById('checkout-items-summary');
    const coSubtotalEl = document.getElementById('co-subtotal-val');
    const coDeliveryEl = document.getElementById('co-delivery-val');
    const coTotalEl = document.getElementById('co-total-val');
    const orderTypeRadios = document.querySelectorAll('input[name="orderType"]');
    const deliveryAddressGroup = document.getElementById('delivery-address-group');
    const deliveryAddressInput = document.getElementById('delivery-address-input');

    // Success Modal Elements
    const successModal = document.getElementById('order-success-modal');
    const successCloseBtn = document.getElementById('success-modal-close-btn');
    const successRefEl = document.getElementById('success-order-ref');
    const successCustomerEl = document.getElementById('success-customer-info');
    const successTotalEl = document.getElementById('success-order-total');
    const successTelegramStatusEl = document.getElementById('success-telegram-status');

    // My Orders Elements
    const myOrdersTriggers = document.querySelectorAll('.my-orders-trigger');
    const myOrdersModal = document.getElementById('my-orders-modal');
    const myOrdersCloseBtn = document.getElementById('my-orders-close-btn');
    const myOrdersList = document.getElementById('my-orders-list');
    const myOrdersEmpty = document.getElementById('my-orders-empty');

    // Mobile Nav
    const navToggleBtn = document.getElementById('nav-toggle-btn');
    const mobileMenu = document.getElementById('mobile-menu');

    let currentCategory = 'all';
    let searchQuery = '';

    // -------------------------------------------------------------------------
    // 1. Menu Rendering & Category Filters
    // -------------------------------------------------------------------------
    function renderMenu() {
        if (!menuGrid) return;

        const filtered = PRODUCTS.filter(product => {
            const matchesCat = (currentCategory === 'all' || product.category === currentCategory);
            const matchesSearch = searchQuery === '' || 
                product.name.toLowerCase().includes(searchQuery) ||
                product.description.toLowerCase().includes(searchQuery);
            return matchesCat && matchesSearch;
        });

        if (filtered.length === 0) {
            menuGrid.innerHTML = `
                <div class="menu-empty-state">
                    <span class="empty-icon">🍽️</span>
                    <h3>No items found</h3>
                    <p>Try searching for a different item or switch category tabs.</p>
                </div>
            `;
            return;
        }

        menuGrid.innerHTML = filtered.map(product => `
            <article class="product-card" data-id="${product.id}">
                <div class="product-image-wrap">
                    <img src="${product.image}" alt="${product.name}" class="product-image" loading="lazy" />
                    ${product.badge ? `<span class="product-badge">${product.badge}</span>` : ''}
                    <span class="product-prep-time">⏱ ${product.prepTime}</span>
                </div>
                <div class="product-content">
                    <div class="product-header">
                        <h3 class="product-title">${product.name}</h3>
                        <div class="product-price">${formatETB(product.price)}</div>
                    </div>
                    <p class="product-desc">${product.description}</p>
                    <div class="product-footer">
                        <button type="button" class="btn btn-gold btn-sm btn-add-cart" data-id="${product.id}">
                            <span>+ Add to Cart</span>
                        </button>
                    </div>
                </div>
            </article>
        `).join('');

        // Attach event listeners to Add to Cart buttons
        menuGrid.querySelectorAll('.btn-add-cart').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const productId = btn.getAttribute('data-id');
                const success = CheddarCart.addItem(productId, 1);
                if (success) {
                    showToast(`Added to cart!`, 'success');
                    animateButton(btn);
                }
            });
        });
    }

    // Category Tabs click handler
    categoryTabs.forEach(tab => {
        tab.addEventListener('click', () => {
            categoryTabs.forEach(t => t.classList.remove('active'));
            tab.classList.add('active');
            currentCategory = tab.getAttribute('data-category');
            renderMenu();
        });
    });

    // Search input handler
    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            searchQuery = e.target.value.trim().toLowerCase();
            renderMenu();
        });
    }

    // Button click animation
    function animateButton(btn) {
        const originalText = btn.innerHTML;
        btn.innerHTML = `<span>✓ Added</span>`;
        btn.classList.add('btn-added');
        setTimeout(() => {
            btn.innerHTML = originalText;
            btn.classList.remove('btn-added');
        }, 1200);
    }

    // -------------------------------------------------------------------------
    // 2. Shopping Cart Drawer & Live State
    // -------------------------------------------------------------------------
    function updateCartUI() {
        const items = CheddarCart.getItems();
        const count = CheddarCart.getCount();
        const subtotal = CheddarCart.getSubtotal();
        const orderType = getSelectedOrderType();
        const deliveryFee = CheddarCart.getDeliveryFee(orderType);
        const total = CheddarCart.getTotal(orderType);

        // Update badge counts
        cartCountBadges.forEach(badge => {
            badge.textContent = count;
            badge.style.display = count > 0 ? 'inline-flex' : 'none';
        });

        // Update Cart Drawer Items
        if (items.length === 0) {
            cartEmptyState.style.display = 'block';
            cartItemsList.style.display = 'none';
            cartSummaryContainer.style.display = 'none';
            if (proceedToCheckoutBtn) proceedToCheckoutBtn.disabled = true;
        } else {
            cartEmptyState.style.display = 'none';
            cartItemsList.style.display = 'block';
            cartSummaryContainer.style.display = 'block';
            if (proceedToCheckoutBtn) proceedToCheckoutBtn.disabled = false;

            cartItemsList.innerHTML = items.map(item => `
                <div class="cart-item-row" data-id="${item.productId}">
                    <img src="${item.image}" alt="${item.name}" class="cart-item-thumb" />
                    <div class="cart-item-info">
                        <h4 class="cart-item-name">${item.name}</h4>
                        <div class="cart-item-unit-price">${formatETB(item.price)} each</div>
                        <div class="cart-item-controls">
                            <div class="qty-stepper">
                                <button type="button" class="qty-btn btn-qty-minus" data-id="${item.productId}">−</button>
                                <span class="qty-value">${item.quantity}</span>
                                <button type="button" class="qty-btn btn-qty-plus" data-id="${item.productId}">+</button>
                            </div>
                            <span class="cart-item-subtotal">${formatETB(item.price * item.quantity)}</span>
                        </div>
                    </div>
                    <button type="button" class="cart-item-remove-btn" data-id="${item.productId}" title="Remove item" aria-label="Remove item">✕</button>
                </div>
            `).join('');

            // Attach cart row listeners
            cartItemsList.querySelectorAll('.btn-qty-minus').forEach(btn => {
                btn.addEventListener('click', () => {
                    const id = btn.getAttribute('data-id');
                    const target = items.find(i => i.productId === id);
                    if (target) CheddarCart.updateQuantity(id, target.quantity - 1);
                });
            });

            cartItemsList.querySelectorAll('.btn-qty-plus').forEach(btn => {
                btn.addEventListener('click', () => {
                    const id = btn.getAttribute('data-id');
                    const target = items.find(i => i.productId === id);
                    if (target) CheddarCart.updateQuantity(id, target.quantity + 1);
                });
            });

            cartItemsList.querySelectorAll('.cart-item-remove-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const id = btn.getAttribute('data-id');
                    CheddarCart.removeItem(id);
                });
            });
        }

        // Update Cart Drawer Summary
        if (cartSubtotalEl) cartSubtotalEl.textContent = formatETB(subtotal);
        if (cartDeliveryFeeEl) cartDeliveryFeeEl.textContent = formatETB(deliveryFee);
        if (cartTotalEl) cartTotalEl.textContent = formatETB(total);
    }

    // Listen for cart state changes
    window.addEventListener('cart:updated', updateCartUI);

    // Cart Drawer Toggle
    function openCart() {
        cartDrawer.classList.add('open');
        cartBackdrop.classList.add('open');
        document.body.classList.add('modal-open');
    }

    function closeCart() {
        cartDrawer.classList.remove('open');
        cartBackdrop.classList.remove('open');
        document.body.classList.remove('modal-open');
    }

    cartToggleBtns.forEach(btn => btn.addEventListener('click', openCart));
    if (cartCloseBtn) cartCloseBtn.addEventListener('click', closeCart);
    if (cartBackdrop) cartBackdrop.addEventListener('click', closeCart);

    // -------------------------------------------------------------------------
    // 3. Checkout Modal Handling
    // -------------------------------------------------------------------------
    function getSelectedOrderType() {
        const checked = document.querySelector('input[name="orderType"]:checked');
        return checked ? checked.value : 'delivery';
    }

    function updateCheckoutSummary() {
        const items = CheddarCart.getItems();
        const subtotal = CheddarCart.getSubtotal();
        const orderType = getSelectedOrderType();
        const deliveryFee = CheddarCart.getDeliveryFee(orderType);
        const total = CheddarCart.getTotal(orderType);

        if (checkoutItemsSummary) {
            checkoutItemsSummary.innerHTML = items.map(item => `
                <div class="checkout-summary-row">
                    <span>${item.quantity} × ${item.name}</span>
                    <strong>${formatETB(item.price * item.quantity)}</strong>
                </div>
            `).join('');
        }

        if (coSubtotalEl) coSubtotalEl.textContent = formatETB(subtotal);
        if (coDeliveryEl) coDeliveryEl.textContent = deliveryFee > 0 ? formatETB(deliveryFee) : 'FREE';
        if (coTotalEl) coTotalEl.textContent = formatETB(total);
    }

    // Toggle Delivery Address visibility based on Order Type
    orderTypeRadios.forEach(radio => {
        radio.addEventListener('change', () => {
            const type = getSelectedOrderType();
            if (type === 'delivery') {
                deliveryAddressGroup.style.display = 'block';
                if (deliveryAddressInput) deliveryAddressInput.required = true;
            } else {
                deliveryAddressGroup.style.display = 'none';
                if (deliveryAddressInput) deliveryAddressInput.required = false;
            }
            updateCheckoutSummary();
            updateCartUI();
        });
    });

    function openCheckout() {
        if (CheddarCart.getItems().length === 0) {
            showToast('Your cart is empty!', 'error');
            return;
        }
        closeCart();
        updateCheckoutSummary();
        clearFormErrors();
        checkoutModal.classList.add('open');
        document.body.classList.add('modal-open');
    }

    function closeCheckout() {
        checkoutModal.classList.remove('open');
        document.body.classList.remove('modal-open');
    }

    if (proceedToCheckoutBtn) proceedToCheckoutBtn.addEventListener('click', openCheckout);
    if (checkoutCloseBtn) checkoutCloseBtn.addEventListener('click', closeCheckout);

    // -------------------------------------------------------------------------
    // 4. Form Validation & Order Submission Flow
    // -------------------------------------------------------------------------
    function clearFormErrors() {
        document.querySelectorAll('.form-error-msg').forEach(el => el.textContent = '');
        document.querySelectorAll('.form-input').forEach(el => el.classList.remove('input-error'));
        const genErr = document.getElementById('checkout-general-error');
        if (genErr) {
            genErr.style.display = 'none';
            genErr.textContent = '';
        }
    }

    function displayFormErrors(errors) {
        clearFormErrors();
        for (const [field, message] of Object.entries(errors)) {
            if (field === 'general' || field === 'cart') {
                const genErr = document.getElementById('checkout-general-error');
                if (genErr) {
                    genErr.textContent = message;
                    genErr.style.display = 'block';
                }
            } else {
                const inputEl = document.getElementById(`field-${field}`);
                const msgEl = document.getElementById(`error-${field}`);
                if (inputEl) inputEl.classList.add('input-error');
                if (msgEl) msgEl.textContent = message;
            }
        }
    }

    if (checkoutForm) {
        checkoutForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            clearFormErrors();

            const formData = {
                customerName: document.getElementById('field-customerName')?.value || '',
                phone: document.getElementById('field-phone')?.value || '',
                orderType: getSelectedOrderType(),
                deliveryAddress: document.getElementById('delivery-address-input')?.value || '',
                instructions: document.getElementById('field-instructions')?.value || ''
            };

            await CheddarCheckout.submitOrder(formData, {
                onStart: () => {
                    checkoutSubmitBtn.disabled = true;
                    checkoutSubmitBtn.innerHTML = `
                        <span class="spinner-sm"></span>
                        <span>Securing & Submitting Order...</span>
                    `;
                },
                onSuccess: (result) => {
                    checkoutSubmitBtn.disabled = false;
                    checkoutSubmitBtn.innerHTML = `<span>Place Order &rarr;</span>`;
                    closeCheckout();
                    openSuccessModal(result);
                },
                onPartialSuccess: (result) => {
                    checkoutSubmitBtn.disabled = false;
                    checkoutSubmitBtn.innerHTML = `<span>Place Order &rarr;</span>`;
                    closeCheckout();
                    openSuccessModal(result);
                },
                onError: (errors) => {
                    checkoutSubmitBtn.disabled = false;
                    checkoutSubmitBtn.innerHTML = `<span>Place Order &rarr;</span>`;
                    displayFormErrors(errors);
                }
            });
        });
    }

    // -------------------------------------------------------------------------
    // 5. Order Success Modal
    // -------------------------------------------------------------------------
    function openSuccessModal(result) {
        if (!successModal) return;

        if (successRefEl) successRefEl.textContent = result.orderReference;
        if (successTotalEl) successTotalEl.textContent = formatETB(result.total);
        if (successCustomerEl) {
            successCustomerEl.innerHTML = `
                <div><strong>Customer:</strong> ${result.customerName}</div>
                <div><strong>Phone:</strong> ${result.phone}</div>
                <div><strong>Fulfillment:</strong> ${result.orderType === 'delivery' ? 'Home Delivery' : 'In-Store Pickup'}</div>
            `;
        }

        if (successTelegramStatusEl) {
            if (result.telegramSent) {
                successTelegramStatusEl.className = 'telegram-status-box status-success';
                successTelegramStatusEl.innerHTML = `
                    <span>📱 <strong>Telegram Notification:</strong> Dispatched immediately to restaurant management. Our dispatch team will call your phone shortly to confirm.</span>
                `;
            } else {
                successTelegramStatusEl.className = 'telegram-status-box status-pending';
                successTelegramStatusEl.innerHTML = `
                    <span>📋 <strong>Order Intake Complete:</strong> Order logged successfully via Formspree. (Telegram notification will activate once bot credentials are configured in Netlify).</span>
                `;
            }
        }

        successModal.classList.add('open');
        document.body.classList.add('modal-open');
    }

    function closeSuccessModal() {
        if (successModal) successModal.classList.remove('open');
        document.body.classList.remove('modal-open');
    }

    if (successCloseBtn) successCloseBtn.addEventListener('click', closeSuccessModal);

    // -------------------------------------------------------------------------
    // 6. "My Orders" History Viewer (LocalStorage)
    // -------------------------------------------------------------------------
    function renderOrderHistory() {
        if (!myOrdersList) return;
        const history = CheddarCheckout.getLocalOrderHistory();

        if (history.length === 0) {
            myOrdersEmpty.style.display = 'block';
            myOrdersList.style.display = 'none';
            return;
        }

        myOrdersEmpty.style.display = 'none';
        myOrdersList.style.display = 'block';

        myOrdersList.innerHTML = history.map(order => `
            <div class="order-history-card">
                <div class="order-history-header">
                    <div>
                        <span class="history-ref">${order.reference}</span>
                        <div class="history-date">${new Date(order.date).toLocaleString()}</div>
                    </div>
                    <div class="history-total">${formatETB(order.total)}</div>
                </div>
                <div class="history-items-list">
                    ${(order.items || []).map(i => `
                        <span>${i.quantity} × ${i.name}</span>
                    `).join(', ')}
                </div>
                <div class="history-footer">
                    <span>${order.orderType === 'delivery' ? '🛵 Delivery' : '🛍️ Pickup'} • ${order.phone}</span>
                    <span class="badge-saved">Saved on this device</span>
                </div>
            </div>
        `).join('');
    }

    function openMyOrders() {
        renderOrderHistory();
        if (myOrdersModal) myOrdersModal.classList.add('open');
        document.body.classList.add('modal-open');
    }

    function closeMyOrders() {
        if (myOrdersModal) myOrdersModal.classList.remove('open');
        document.body.classList.remove('modal-open');
    }

    myOrdersTriggers.forEach(btn => btn.addEventListener('click', (e) => {
        e.preventDefault();
        openMyOrders();
    }));

    if (myOrdersCloseBtn) myOrdersCloseBtn.addEventListener('click', closeMyOrders);

    // -------------------------------------------------------------------------
    // 7. Toast Notification System
    // -------------------------------------------------------------------------
    function showToast(message, type = 'success') {
        let toastContainer = document.getElementById('toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.id = 'toast-container';
            toastContainer.className = 'toast-container';
            document.body.appendChild(toastContainer);
        }

        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.innerHTML = `
            <span class="toast-icon">${type === 'success' ? '✓' : '⚠️'}</span>
            <span class="toast-msg">${message}</span>
        `;

        toastContainer.appendChild(toast);

        setTimeout(() => toast.classList.add('visible'), 10);
        setTimeout(() => {
            toast.classList.remove('visible');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    // -------------------------------------------------------------------------
    // 8. Mobile Navigation Toggle
    // -------------------------------------------------------------------------
    if (navToggleBtn && mobileMenu) {
        navToggleBtn.addEventListener('click', () => {
            const isOpen = mobileMenu.classList.toggle('open');
            navToggleBtn.setAttribute('aria-expanded', isOpen);
            navToggleBtn.innerHTML = isOpen ? '✕' : '☰';
        });

        // Close mobile menu when a navigation link is clicked
        mobileMenu.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.classList.remove('open');
                navToggleBtn.innerHTML = '☰';
            });
        });
    }

    // Initial render
    renderMenu();
    updateCartUI();
});
