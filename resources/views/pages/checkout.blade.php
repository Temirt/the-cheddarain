@extends('layouts.app')

@section('title', 'Secure Checkout — The Cheddarain')

@section('content')
<div class="container" style="padding-top: 3.5rem; padding-bottom: 5rem;">
    <div class="section-title">
        <span class="badge badge-cheddar">Final Step</span>
        <h2>Complete Your Order</h2>
        <p>Confirm your contact details and fulfillment choice to place your order.</p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <div>
                <strong>Please fix the following errors:</strong>
                <ul style="margin-left: 1.5rem; margin-top: 0.5rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div id="checkout-empty-notice" style="display: none; text-align: center; padding: 4rem 1rem;">
        <div style="font-size: 3rem; margin-bottom: 1rem;">🛒</div>
        <h3>No Items in Cart</h3>
        <p style="color: var(--text-cream-muted); margin-bottom: 1.5rem;">Please add items from our menu before proceeding to checkout.</p>
        <a href="{{ route('menu') }}" class="btn btn-primary">Browse Menu</a>
    </div>

    <form action="{{ route('checkout.process') }}" method="POST" id="checkout-form" style="display: none;">
        @csrf
        <div id="hidden-cart-inputs"></div>

        <div style="display: grid; grid-template-columns: 1.25fr 0.75fr; gap: 3rem; align-items: start;">
            <!-- Checkout Form Fields -->
            <div>
                <!-- Contact Information -->
                <div style="background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-lg); padding: 2rem; margin-bottom: 2rem;">
                    <h3 style="font-size: 1.25rem; margin-bottom: 1.5rem; padding-bottom: 0.5rem; border-bottom: 1px solid var(--border-subtle); display: flex; align-items: center; gap: 0.6rem;">
                        <span>👤</span> 1. Contact Information
                    </h3>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.2rem;">
                        <div class="form-group" style="grid-column: 1 / -1;">
                            <label class="form-label" for="customer_name">Full Name *</label>
                            <input type="text" name="customer_name" id="customer_name" class="form-input" required value="{{ old('customer_name', auth()->user()->name ?? '') }}" placeholder="e.g. Michael Sterling">
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="customer_phone">Phone Number * (Used for order tracking verification)</label>
                            <input type="tel" name="customer_phone" id="customer_phone" class="form-input" required value="{{ old('customer_phone', auth()->user()->phone ?? '') }}" placeholder="e.g. 555-123-4567">
                            <span class="form-hint">You will use this number to track your order in real time.</span>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="customer_email">Email Address *</label>
                            <input type="email" name="customer_email" id="customer_email" class="form-input" required value="{{ old('customer_email', auth()->user()->email ?? '') }}" placeholder="e.g. michael@example.com">
                        </div>
                    </div>
                </div>

                <!-- Fulfillment Option -->
                <div style="background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-lg); padding: 2rem; margin-bottom: 2rem;">
                    <h3 style="font-size: 1.25rem; margin-bottom: 1.5rem; padding-bottom: 0.5rem; border-bottom: 1px solid var(--border-subtle); display: flex; align-items: center; gap: 0.6rem;">
                        <span>🛵</span> 2. Fulfillment Method
                    </h3>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                        <label class="option-item-row" style="padding: 1.2rem; cursor: pointer;">
                            <div class="option-label-info">
                                <input type="radio" name="fulfillment_type" value="delivery" id="fulfillment_delivery" {{ old('fulfillment_type', 'delivery') === 'delivery' ? 'checked' : '' }}>
                                <div>
                                    <strong style="display: block; color: var(--text-cream);">Doorstep Delivery</strong>
                                    <span style="font-size: 0.8rem; color: var(--text-cream-muted);">Delivered hot to your location</span>
                                </div>
                            </div>
                        </label>

                        <label class="option-item-row" style="padding: 1.2rem; cursor: pointer;">
                            <div class="option-label-info">
                                <input type="radio" name="fulfillment_type" value="pickup" id="fulfillment_pickup" {{ old('fulfillment_type') === 'pickup' ? 'checked' : '' }}>
                                <div>
                                    <strong style="display: block; color: var(--text-cream);">Counter Pickup</strong>
                                    <span style="font-size: 0.8rem; color: var(--text-cream-muted);">Ready at 108 Artisan Burger Way</span>
                                </div>
                            </div>
                        </label>
                    </div>

                    <!-- Delivery Address Fields (Shown only when Delivery is selected) -->
                    <div id="delivery-address-container" style="display: flex; flex-direction: column; gap: 1.2rem;">
                        <div class="form-group">
                            <label class="form-label" for="delivery_address">Delivery Street Address *</label>
                            <input type="text" name="delivery_address" id="delivery_address" class="form-input" value="{{ old('delivery_address') }}" placeholder="Street name and building number">
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.2rem;">
                            <div class="form-group">
                                <label class="form-label" for="delivery_apartment">Apt / Suite / Unit (Optional)</label>
                                <input type="text" name="delivery_apartment" id="delivery_apartment" class="form-input" value="{{ old('delivery_apartment') }}" placeholder="e.g. Apt 4B">
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="delivery_city">City *</label>
                                <input type="text" name="delivery_city" id="delivery_city" class="form-input" value="{{ old('delivery_city', 'Springfield') }}" placeholder="City">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="delivery_notes">Delivery Instructions for Driver</label>
                            <input type="text" name="delivery_notes" id="delivery_notes" class="form-input" value="{{ old('delivery_notes') }}" placeholder="e.g. Gate code #1234, leave by front porch">
                        </div>
                    </div>

                    <div class="form-group" style="margin-top: 1rem;">
                        <label class="form-label" for="special_instructions">Kitchen Special Instructions</label>
                        <textarea name="special_instructions" id="special_instructions" class="form-textarea" placeholder="e.g. Extra napkins, no ice in drink">{{ old('special_instructions') }}</textarea>
                    </div>
                </div>

                <!-- Payment Selection -->
                <div style="background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-lg); padding: 2rem;">
                    <h3 style="font-size: 1.25rem; margin-bottom: 1.5rem; padding-bottom: 0.5rem; border-bottom: 1px solid var(--border-subtle); display: flex; align-items: center; gap: 0.6rem;">
                        <span>💳</span> 3. Payment Method
                    </h3>

                    <div style="display: flex; flex-direction: column; gap: 0.8rem;">
                        <label class="option-item-row" style="padding: 1rem;">
                            <div class="option-label-info">
                                <input type="radio" name="payment_method" value="cash_on_delivery" checked id="pay_cod">
                                <div>
                                    <strong style="color: var(--text-cream); display: block;">Cash on Delivery</strong>
                                    <span style="font-size: 0.82rem; color: var(--text-cream-muted);">Pay cash to driver upon arrival</span>
                                </div>
                            </div>
                            <span class="badge badge-cheddar">Popular</span>
                        </label>

                        <label class="option-item-row" style="padding: 1rem;">
                            <div class="option-label-info">
                                <input type="radio" name="payment_method" value="payment_on_pickup" id="pay_pickup">
                                <div>
                                    <strong style="color: var(--text-cream); display: block;">Payment at Pickup Counter</strong>
                                    <span style="font-size: 0.82rem; color: var(--text-cream-muted);">Pay by Cash, Card, or Contactless in restaurant</span>
                                </div>
                            </div>
                        </label>

                        <label class="option-item-row" style="padding: 1rem;">
                            <div class="option-label-info">
                                <input type="radio" name="payment_method" value="online_card" id="pay_card">
                                <div>
                                    <strong style="color: var(--text-cream); display: block;">Credit / Debit Card (Online Demo)</strong>
                                    <span style="font-size: 0.82rem; color: var(--text-cream-muted);">Simulated secure payment gateway</span>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Order Review Sidebar -->
            <div>
                <div class="cart-summary-box" style="position: sticky; top: calc(var(--nav-height) + 20px);">
                    <h3 style="font-size: 1.25rem; margin-bottom: 1.5rem; padding-bottom: 0.75rem; border-bottom: 1px solid var(--border-subtle);">
                        Review Order
                    </h3>

                    <div id="checkout-items-list" style="max-height: 280px; overflow-y: auto; margin-bottom: 1.5rem; display: flex; flex-direction: column; gap: 0.8rem;">
                        <!-- Items rendered via Vanilla JS -->
                    </div>

                    <div class="summary-row">
                        <span>Items Subtotal</span>
                        <span id="co-subtotal">$0.00</span>
                    </div>

                    <div class="summary-row" id="co-delivery-row">
                        <span>Delivery Fee</span>
                        <span id="co-delivery-fee">${{ number_format($deliveryFee, 2) }}</span>
                    </div>

                    <div class="summary-row">
                        <span>Tax ({{ $taxRate }}%)</span>
                        <span id="co-tax">$0.00</span>
                    </div>

                    <div class="summary-row total-row">
                        <span>Grand Total</span>
                        <span id="co-total">$0.00</span>
                    </div>

                    <div style="margin-top: 2rem;">
                        <button type="submit" class="btn btn-primary btn-block btn-lg" id="submit-order-btn">
                            <span>Place Order Now</span>
                            <span>&rarr;</span>
                        </button>
                    </div>

                    <p style="text-align: center; font-size: 0.8rem; color: var(--text-dim); margin-top: 1.2rem;">
                        🔒 All orders are verified and server-calculated. You will receive a unique tracking reference immediately.
                    </p>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const deliveryFeeDefault = {{ $deliveryFee }};
    const taxRatePercent = {{ $taxRate }};

    const emptyNotice = document.getElementById('checkout-empty-notice');
    const checkoutForm = document.getElementById('checkout-form');
    const hiddenInputs = document.getElementById('hidden-cart-inputs');
    const itemsList = document.getElementById('checkout-items-list');

    const subtotalEl = document.getElementById('co-subtotal');
    const deliveryFeeEl = document.getElementById('co-delivery-fee');
    const taxEl = document.getElementById('co-tax');
    const totalEl = document.getElementById('co-total');

    const deliveryRadio = document.getElementById('fulfillment_delivery');
    const pickupRadio = document.getElementById('fulfillment_pickup');
    const deliveryAddressContainer = document.getElementById('delivery-address-container');

    const items = Cart.getItems();

    if (items.length === 0) {
        emptyNotice.style.display = 'block';
        checkoutForm.style.display = 'none';
        return;
    }

    emptyNotice.style.display = 'none';
    checkoutForm.style.display = 'block';

    // Populate review list & hidden inputs for server form submission
    let reviewHtml = '';
    let inputsHtml = '';

    items.forEach((item, index) => {
        const optionsText = (item.options && item.options.length > 0)
            ? item.options.map(o => o.choice).join(', ')
            : '';

        reviewHtml += `
            <div style="display: flex; justify-content: space-between; align-items: start; font-size: 0.92rem; padding-bottom: 0.6rem; border-bottom: 1px solid var(--border-subtle);">
                <div>
                    <strong>${item.quantity}x ${item.name}</strong>
                    ${optionsText ? `<div style="font-size: 0.78rem; color: var(--text-cream-muted);">${optionsText}</div>` : ''}
                </div>
                <div style="font-weight: 700; color: var(--gold-bright);">$${parseFloat(item.subtotal).toFixed(2)}</div>
            </div>
        `;

        inputsHtml += `
            <input type="hidden" name="cart_items[${index}][product_id]" value="${item.product_id}">
            <input type="hidden" name="cart_items[${index}][quantity]" value="${item.quantity}">
        `;

        if (item.options && item.options.length > 0) {
            item.options.forEach((opt, optIdx) => {
                inputsHtml += `
                    <input type="hidden" name="cart_items[${index}][options][${optIdx}][option_name]" value="${opt.option_name}">
                    <input type="hidden" name="cart_items[${index}][options][${optIdx}][value_id]" value="${opt.value_id}">
                `;
            });
        }
    });

    itemsList.innerHTML = reviewHtml;
    hiddenInputs.innerHTML = inputsHtml;

    function recalculateTotals() {
        const subtotal = Cart.getSubtotal();
        const isDelivery = deliveryRadio.checked;
        const currentDeliveryFee = isDelivery ? deliveryFeeDefault : 0.00;
        const tax = Math.round(subtotal * (taxRatePercent / 100) * 100) / 100;
        const total = subtotal + currentDeliveryFee + tax;

        subtotalEl.textContent = `$${subtotal.toFixed(2)}`;
        deliveryFeeEl.textContent = isDelivery ? `$${deliveryFeeDefault.toFixed(2)}` : 'FREE';
        taxEl.textContent = `$${tax.toFixed(2)}`;
        totalEl.textContent = `$${total.toFixed(2)}`;

        deliveryAddressContainer.style.display = isDelivery ? 'flex' : 'none';
        const addressInput = document.getElementById('delivery_address');
        const cityInput = document.getElementById('delivery_city');
        if (addressInput) addressInput.required = isDelivery;
        if (cityInput) cityInput.required = isDelivery;
    }

    deliveryRadio.addEventListener('change', recalculateTotals);
    pickupRadio.addEventListener('change', recalculateTotals);

    recalculateTotals();

    checkoutForm.addEventListener('submit', () => {
        const btn = document.getElementById('submit-order-btn');
        btn.disabled = true;
        btn.innerHTML = '<span>Cooking Order & Submitting...</span>';
    });
});
</script>
@endpush
@endsection
