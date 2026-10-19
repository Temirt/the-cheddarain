@extends('layouts.admin')

@section('title', 'Restaurant Settings — The Cheddarain Admin')

@section('admin_content')
<div style="max-width: 800px;">
    <div style="margin-bottom: 2rem;">
        <h1 style="font-size: 2rem; margin-bottom: 0.3rem;">Restaurant Settings</h1>
        <p style="color: var(--text-cream-muted); font-size: 0.95rem;">Configure brand details, WhatsApp support lines, delivery fees, and operating hours.</p>
    </div>

    <div style="background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-lg); padding: 2.2rem;">
        <form action="{{ route('admin.settings.update') }}" method="POST">
            @csrf

            <!-- Section 1: Brand & Contact -->
            <h3 style="font-size: 1.25rem; color: var(--gold-bright); margin-bottom: 1.2rem; padding-bottom: 0.5rem; border-bottom: 1px solid var(--border-subtle);">
                1. Brand & Contact Information
            </h3>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.2rem; margin-bottom: 2rem;">
                <div class="form-group">
                    <label class="form-label" for="restaurant_name">Restaurant Name *</label>
                    <input type="text" name="restaurant_name" id="restaurant_name" class="form-input" required value="{{ old('restaurant_name', $settings['restaurant_name']->value ?? 'The Cheddarain') }}">
                </div>

                <div class="form-group">
                    <label class="form-label" for="restaurant_tagline">Tagline</label>
                    <input type="text" name="restaurant_tagline" id="restaurant_tagline" class="form-input" value="{{ old('restaurant_tagline', $settings['restaurant_tagline']->value ?? '') }}">
                </div>

                <div class="form-group">
                    <label class="form-label" for="whatsapp_number">WhatsApp Number (For customer enquiries) *</label>
                    <input type="text" name="whatsapp_number" id="whatsapp_number" class="form-input" required value="{{ old('whatsapp_number', $settings['whatsapp_number']->value ?? '15552433327') }}">
                    <span class="form-hint">Used for WhatsApp enquiry button (include country code, digits only)</span>
                </div>

                <div class="form-group">
                    <label class="form-label" for="restaurant_phone">Public Phone Number *</label>
                    <input type="text" name="restaurant_phone" id="restaurant_phone" class="form-input" required value="{{ old('restaurant_phone', $settings['restaurant_phone']->value ?? '+1 (555) 243-3327') }}">
                </div>

                <div class="form-group" style="grid-column: 1 / -1;">
                    <label class="form-label" for="restaurant_email">Customer Service Email *</label>
                    <input type="email" name="restaurant_email" id="restaurant_email" class="form-input" required value="{{ old('restaurant_email', $settings['restaurant_email']->value ?? 'orders@thecheddarain.com') }}">
                </div>

                <div class="form-group" style="grid-column: 1 / -1;">
                    <label class="form-label" for="restaurant_address">Store Physical Address *</label>
                    <input type="text" name="restaurant_address" id="restaurant_address" class="form-input" required value="{{ old('restaurant_address', $settings['restaurant_address']->value ?? '108 Artisan Burger Way, Melt District') }}">
                </div>

                <div class="form-group" style="grid-column: 1 / -1;">
                    <label class="form-label" for="opening_hours">Opening Hours *</label>
                    <input type="text" name="opening_hours" id="opening_hours" class="form-input" required value="{{ old('opening_hours', $settings['opening_hours']->value ?? 'Monday - Sunday: 11:00 AM - 11:00 PM') }}">
                </div>
            </div>

            <!-- Section 2: Financial & Delivery Settings -->
            <h3 style="font-size: 1.25rem; color: var(--gold-bright); margin-bottom: 1.2rem; padding-bottom: 0.5rem; border-bottom: 1px solid var(--border-subtle);">
                2. Pricing & Delivery Configuration
            </h3>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1.2rem; margin-bottom: 2rem;">
                <div class="form-group">
                    <label class="form-label" for="delivery_fee">Delivery Fee ($) *</label>
                    <input type="number" step="0.01" name="delivery_fee" id="delivery_fee" class="form-input" required value="{{ old('delivery_fee', $settings['delivery_fee']->value ?? '3.99') }}">
                </div>

                <div class="form-group">
                    <label class="form-label" for="tax_rate_percent">Estimated Tax Rate (%) *</label>
                    <input type="number" step="0.1" name="tax_rate_percent" id="tax_rate_percent" class="form-input" required value="{{ old('tax_rate_percent', $settings['tax_rate_percent']->value ?? '8.5') }}">
                </div>

                <div class="form-group">
                    <label class="form-label" for="currency_symbol">Currency Symbol *</label>
                    <input type="text" name="currency_symbol" id="currency_symbol" class="form-input" required value="{{ old('currency_symbol', $settings['currency_symbol']->value ?? '$') }}">
                </div>
            </div>

            <!-- Section 3: Enabled Options -->
            <h3 style="font-size: 1.25rem; color: var(--gold-bright); margin-bottom: 1.2rem; padding-bottom: 0.5rem; border-bottom: 1px solid var(--border-subtle);">
                3. Operations & Payment Methods
            </h3>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.2rem; margin-bottom: 2rem;">
                <label style="display: flex; align-items: center; gap: 0.6rem; cursor: pointer;">
                    <input type="checkbox" name="enable_pickup" value="1" {{ ($settings['enable_pickup']->value ?? '1') == '1' ? 'checked' : '' }} style="accent-color: var(--cheddar-bright);">
                    <span>Enable Customer Pickup Option</span>
                </label>

                <label style="display: flex; align-items: center; gap: 0.6rem; cursor: pointer;">
                    <input type="checkbox" name="enable_delivery" value="1" {{ ($settings['enable_delivery']->value ?? '1') == '1' ? 'checked' : '' }} style="accent-color: var(--cheddar-bright);">
                    <span>Enable Doorstep Delivery Option</span>
                </label>

                <label style="display: flex; align-items: center; gap: 0.6rem; cursor: pointer;">
                    <input type="checkbox" name="enable_cash_on_delivery" value="1" {{ ($settings['enable_cash_on_delivery']->value ?? '1') == '1' ? 'checked' : '' }} style="accent-color: var(--cheddar-bright);">
                    <span>Accept Cash on Delivery (COD)</span>
                </label>

                <label style="display: flex; align-items: center; gap: 0.6rem; cursor: pointer;">
                    <input type="checkbox" name="enable_payment_on_pickup" value="1" {{ ($settings['enable_payment_on_pickup']->value ?? '1') == '1' ? 'checked' : '' }} style="accent-color: var(--cheddar-bright);">
                    <span>Accept Payment at Pickup Counter</span>
                </label>
            </div>

            <button type="submit" class="btn btn-primary btn-lg">
                <span>Save Store Settings &rarr;</span>
            </button>
        </form>
    </div>
</div>
@endsection
