@extends('layouts.app')

@section('title', 'Shopping Cart — The Cheddarloin')

@section('content')
<div class="container" style="padding-top: 3.5rem; padding-bottom: 5rem;">
    <div class="section-title">
        <span class="badge badge-cheddar">Your Selection</span>
        <h2>Shopping Cart</h2>
        <p>Review your artisanal items before heading to secure checkout.</p>
    </div>

    <div id="cart-empty-view" style="display: none; text-align: center; padding: 5rem 1rem;">
        <div style="font-size: 4rem; margin-bottom: 1.2rem;">🛒</div>
        <h3>Your Cart is Currently Empty</h3>
        <p style="color: var(--text-cream-muted); margin-bottom: 2rem;">Looks like you haven't added any melted cheddar goodness yet.</p>
        <a href="{{ route('menu') }}" class="btn btn-primary btn-lg">Browse Menu</a>
    </div>

    <div id="cart-content-view" style="display: none;">
        <div style="display: grid; grid-template-columns: 1.35fr 0.65fr; gap: 2.5rem; align-items: start;">
            <!-- Cart Items Table -->
            <div>
                <div class="cart-table-wrapper">
                    <table class="cart-table">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="cart-table-body">
                            <!-- Populated dynamically via Vanilla JS -->
                        </tbody>
                    </table>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: gap; margin-top: 1.5rem;">
                    <a href="{{ route('menu') }}" class="btn btn-secondary">
                        <span>&larr; Add More Items</span>
                    </a>
                    <button type="button" class="btn btn-outline-gold btn-sm" id="clear-cart-btn">
                        <span>Clear All Items</span>
                    </button>
                </div>
            </div>

            <!-- Order Summary & Fulfillment Switch -->
            <div>
                <div class="cart-summary-box">
                    <h3 style="font-size: 1.3rem; margin-bottom: 1.5rem; padding-bottom: 0.75rem; border-bottom: 1px solid var(--border-subtle);">
                        Order Summary
                    </h3>

                    <!-- Fulfillment Method Selector -->
                    <div style="margin-bottom: 1.5rem;">
                        <label class="form-label">Fulfillment Method</label>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.6rem; margin-top: 0.5rem;">
                            <label class="option-item-row" style="margin-bottom: 0; padding: 0.75rem;">
                                <div class="option-label-info">
                                    <input type="radio" name="cart_fulfillment" value="delivery" checked id="fulfillment-delivery">
                                    <span style="font-size: 0.9rem;">🛵 Delivery</span>
                                </div>
                            </label>
                            <label class="option-item-row" style="margin-bottom: 0; padding: 0.75rem;">
                                <div class="option-label-info">
                                    <input type="radio" name="cart_fulfillment" value="pickup" id="fulfillment-pickup">
                                    <span style="font-size: 0.9rem;">🛍️ Pickup</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span id="summary-subtotal">$0.00</span>
                    </div>

                    <div class="summary-row" id="summary-delivery-row">
                        <span>Delivery Fee</span>
                        <span id="summary-delivery-fee">${{ number_format($deliveryFee, 2) }}</span>
                    </div>

                    <div class="summary-row">
                        <span>Estimated Tax ({{ $taxRate }}%)</span>
                        <span id="summary-tax">$0.00</span>
                    </div>

                    <div class="summary-row total-row">
                        <span>Estimated Total</span>
                        <span id="summary-total">$0.00</span>
                    </div>

                    <div style="margin-top: 2rem;">
                        <a href="{{ route('checkout') }}" class="btn btn-primary btn-block btn-lg" id="proceed-checkout-btn">
                            Proceed to Checkout &rarr;
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const deliveryFeeDefault = {{ $deliveryFee }};
    const taxRatePercent = {{ $taxRate }};

    const emptyView = document.getElementById('cart-empty-view');
    const contentView = document.getElementById('cart-content-view');
    const tbody = document.getElementById('cart-table-body');

    const subtotalEl = document.getElementById('summary-subtotal');
    const deliveryFeeEl = document.getElementById('summary-delivery-fee');
    const deliveryRowEl = document.getElementById('summary-delivery-row');
    const taxEl = document.getElementById('summary-tax');
    const totalEl = document.getElementById('summary-total');

    const deliveryRadio = document.getElementById('fulfillment-delivery');
    const pickupRadio = document.getElementById('fulfillment-pickup');

    function renderCart() {
        const items = Cart.getItems();

        if (items.length === 0) {
            emptyView.style.display = 'block';
            contentView.style.display = 'none';
            return;
        }

        emptyView.style.display = 'none';
        contentView.style.display = 'block';

        let html = '';
        items.forEach(item => {
            const optionsText = (item.options && item.options.length > 0)
                ? item.options.map(o => `${o.choice} (+$${parseFloat(o.price).toFixed(2)})`).join(', ')
                : '';

            html += `
                <tr>
                    <td>
                        <div class="cart-product-cell">
                            <img src="${item.image_url || 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?auto=format&fit=crop&w=150&q=80'}" class="cart-thumb" alt="${item.name}">
                            <div>
                                <div class="cart-item-title">${item.name}</div>
                                ${optionsText ? `<div class="cart-item-options">${optionsText}</div>` : ''}
                            </div>
                        </div>
                    </td>
                    <td style="font-weight: 600; color: var(--gold-bright);">$${parseFloat(item.unit_price).toFixed(2)}</td>
                    <td>
                        <div class="qty-control">
                            <button type="button" class="qty-btn" onclick="updateQty(${item.product_id}, '${item.options_signature}', ${item.quantity - 1})">-</button>
                            <span class="qty-value">${item.quantity}</span>
                            <button type="button" class="qty-btn" onclick="updateQty(${item.product_id}, '${item.options_signature}', ${item.quantity + 1})">+</button>
                        </div>
                    </td>
                    <td style="font-weight: 700; color: var(--text-cream);">$${parseFloat(item.subtotal).toFixed(2)}</td>
                    <td>
                        <button type="button" onclick="removeItem(${item.product_id}, '${item.options_signature}')" style="background: transparent; border: none; color: var(--danger); font-size: 1.1rem; cursor: pointer;" title="Remove">✕</button>
                    </td>
                </tr>
            `;
        });

        tbody.innerHTML = html;

        // Recalculate summary
        const subtotal = Cart.getSubtotal();
        const isDelivery = deliveryRadio.checked;
        const currentDeliveryFee = isDelivery ? deliveryFeeDefault : 0.00;
        const tax = Math.round(subtotal * (taxRatePercent / 100) * 100) / 100;
        const total = subtotal + currentDeliveryFee + tax;

        subtotalEl.textContent = `$${subtotal.toFixed(2)}`;
        deliveryFeeEl.textContent = isDelivery ? `$${deliveryFeeDefault.toFixed(2)}` : 'FREE';
        taxEl.textContent = `$${tax.toFixed(2)}`;
        totalEl.textContent = `$${total.toFixed(2)}`;
    }

    window.updateQty = (id, sig, qty) => {
        Cart.updateQuantity(id, sig, qty);
        renderCart();
    };

    window.removeItem = (id, sig) => {
        Cart.removeItem(id, sig);
        renderCart();
    };

    document.getElementById('clear-cart-btn').addEventListener('click', () => {
        if (confirm('Are you sure you want to empty your cart?')) {
            Cart.clear();
            renderCart();
        }
    });

    deliveryRadio.addEventListener('change', renderCart);
    pickupRadio.addEventListener('change', renderCart);

    renderCart();
});
</script>
@endpush
@endsection
