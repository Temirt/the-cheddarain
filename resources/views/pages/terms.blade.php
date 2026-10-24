@extends('layouts.app')

@section('title', 'Terms & Conditions — The Cheddarloin')

@section('content')
<div class="container container-narrow section-spacing">
    <div style="text-align: center; margin-bottom: 3rem;">
        <span class="badge badge-gold" style="margin-bottom: 0.8rem;">Legal Notice</span>
        <h1 style="font-size: 2.4rem; color: var(--gold-primary); margin-bottom: 0.5rem;">Terms & Conditions</h1>
        <p style="color: var(--text-secondary);">Last updated: {{ date('F Y') }}</p>
    </div>

    <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-lg); padding: 2.5rem; line-height: 1.8; color: var(--text-primary);">
        <h3 style="color: var(--gold-light); margin-bottom: 0.8rem;">1. Ordering & Acceptance</h3>
        <p style="color: var(--text-secondary); margin-bottom: 1.5rem;">
            When you place an order with The Cheddarloin, our kitchen immediately receives your ticket. Orders are prepared fresh to order. By placing an order, you agree to settle payment upon fulfillment (via Cash on Delivery or in-person at the pickup counter).
        </p>

        <h3 style="color: var(--gold-light); margin-bottom: 0.8rem;">2. Pricing & Currency</h3>
        <p style="color: var(--text-secondary); margin-bottom: 1.5rem;">
            All menu prices are clearly displayed in Ethiopian Birr (ETB) and include applicable taxes unless specified otherwise. We reserve the right to modify prices and delivery surcharges based on operational requirements.
        </p>

        <h3 style="color: var(--gold-light); margin-bottom: 0.8rem;">3. Cancellations & Modifications</h3>
        <p style="color: var(--text-secondary); margin-bottom: 1.5rem;">
            Because all gourmet smash burgers and hand-cut fries are prepared fresh to order, order cancellations must be requested within 5 minutes of placing your order. Please contact our dispatch desk directly via phone or WhatsApp.
        </p>

        <h3 style="color: var(--gold-light); margin-bottom: 0.8rem;">4. Delivery Times</h3>
        <p style="color: var(--text-secondary); margin-bottom: 1.5rem;">
            Estimated delivery times are between 25 and 45 minutes depending on traffic and kitchen volume. While our delivery fleet works diligently to deliver every burger piping hot, transit delays may occasionally occur.
        </p>

        <h3 style="color: var(--gold-light); margin-bottom: 0.8rem;">5. Contact Information</h3>
        <p style="color: var(--text-secondary);">
            For questions regarding our terms, reach us at {{ \App\Models\RestaurantSetting::get('restaurant_phone', '+251 (911) 234-567') }} or visit us at {{ \App\Models\RestaurantSetting::get('restaurant_address', 'Bole Sub-City, Addis Ababa, Ethiopia') }}.
        </p>
    </div>
</div>
@endsection
