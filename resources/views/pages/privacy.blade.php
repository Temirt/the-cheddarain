@extends('layouts.app')

@section('title', 'Privacy Policy — The Cheddarloin')

@section('content')
<div class="container container-narrow section-spacing">
    <div style="text-align: center; margin-bottom: 3rem;">
        <span class="badge badge-gold" style="margin-bottom: 0.8rem;">Data Protection</span>
        <h1 style="font-size: 2.4rem; color: var(--gold-primary); margin-bottom: 0.5rem;">Privacy Policy</h1>
        <p style="color: var(--text-secondary);">Last updated: {{ date('F Y') }}</p>
    </div>

    <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-lg); padding: 2.5rem; line-height: 1.8; color: var(--text-primary);">
        <h3 style="color: var(--gold-light); margin-bottom: 0.8rem;">1. Information We Collect</h3>
        <p style="color: var(--text-secondary); margin-bottom: 1.5rem;">
            To process food delivery and pickup orders, we collect your name, phone number, delivery address, and order items. No unnecessary personal data or credit card numbers are collected through this website because payment is handled strictly in person.
        </p>

        <h3 style="color: var(--gold-light); margin-bottom: 0.8rem;">2. Use of LocalStorage</h3>
        <p style="color: var(--text-secondary); margin-bottom: 1.5rem;">
            We use your browser's local storage (localStorage) solely to remember your temporary cart items and order reference numbers across page reloads. This data remains on your local device and is not shared with third parties.
        </p>

        <h3 style="color: var(--gold-light); margin-bottom: 0.8rem;">3. Order Fulfillment & Notifications</h3>
        <p style="color: var(--text-secondary); margin-bottom: 1.5rem;">
            Your phone number is used exclusively by our delivery riders and dispatch staff to coordinate pickup and delivery. We do not sell, rent, or lease customer contact lists to advertisers or third parties.
        </p>

        <h3 style="color: var(--gold-light); margin-bottom: 0.8rem;">4. Data Retention & Security</h3>
        <p style="color: var(--text-secondary);">
            Our administrative databases are secured behind enterprise firewall safeguards. If you wish to request the removal of past order records, contact our management desk at {{ \App\Models\RestaurantSetting::get('restaurant_email', 'orders@thecheddarloin.com') }}.
        </p>
    </div>
</div>
@endsection
