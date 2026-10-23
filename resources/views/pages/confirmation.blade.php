@extends('layouts.app')

@section('title', 'Order Confirmed #' . $order->order_reference . ' — The Cheddarloin')

@section('content')
<div class="container container-narrow" style="padding-top: 4rem; padding-bottom: 6rem; text-align: center;">
    <div style="width: 80px; height: 80px; background: rgba(16, 185, 129, 0.15); border: 2px solid var(--success); border-radius: var(--radius-full); display: flex; align-items: center; justify-content: center; font-size: 2.5rem; color: var(--success); margin: 0 auto 1.8rem auto; box-shadow: 0 0 25px rgba(16, 185, 129, 0.3);">
        ✓
    </div>

    <span class="badge badge-success" style="margin-bottom: 1rem;">Order Placed Successfully</span>
    <h1 style="font-size: 2.6rem; margin-bottom: 1rem;">We've Got Your Order!</h1>
    <p style="color: var(--text-cream-muted); font-size: 1.15rem; max-width: 580px; margin: 0 auto 2.5rem auto;">
        Your order has been received by our kitchen team and is queued for preparation.
    </p>

    <!-- Prominent Reference Card -->
    <div style="background: var(--bg-card); border: 2px dashed var(--cheddar-primary); border-radius: var(--radius-xl); padding: 2.5rem; margin-bottom: 2rem; box-shadow: var(--shadow-lg);">
        <div style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.1em; color: var(--text-cream-muted); margin-bottom: 0.4rem;">
            Your Unique Order Reference
        </div>
        <div style="font-family: var(--font-heading); font-size: 2.6rem; font-weight: 800; color: var(--cheddar-bright); letter-spacing: 0.05em; margin-bottom: 1rem;">
            #{{ $order->order_reference }}
        </div>
        <p style="color: var(--text-cream-soft); font-size: 0.95rem;">
            Keep this reference handy. You can track live kitchen progress at any time using this reference and your phone number: <strong>{{ substr($order->customer_phone, -4) }}</strong>.
        </p>

        <div style="display: flex; gap: 1rem; justify-content: center; margin-top: 2rem; flex-wrap: wrap;">
            <a href="{{ route('order.track.show', $order->order_reference) }}" class="btn btn-primary btn-lg">
                <span>📍</span>
                <span>Track My Order in Real-Time &rarr;</span>
            </a>
            <a href="{{ $order->whatsapp_link }}" target="_blank" rel="noopener noreferrer" class="btn btn-whatsapp btn-lg">
                <span>💬</span>
                <span>Enquire via WhatsApp</span>
            </a>
        </div>
    </div>

    <!-- In-Person Payment Instructions -->
    <div style="background: linear-gradient(135deg, rgba(255, 153, 0, 0.15), rgba(255, 107, 0, 0.06)); border: 2px solid var(--cheddar-primary); border-radius: var(--radius-xl); padding: 2rem; margin-bottom: 2.5rem; text-align: left; box-shadow: var(--shadow-md);">
        <div style="display: flex; align-items: center; gap: 0.85rem; margin-bottom: 1rem;">
            <div style="width: 48px; height: 48px; border-radius: var(--radius-full); background: rgba(255, 153, 0, 0.2); border: 1px solid var(--cheddar-primary); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; flex-shrink: 0;">
                💵
            </div>
            <div>
                <h3 style="font-size: 1.3rem; color: var(--gold-bright); margin: 0;">Payment in Person — Bring Your Order ID</h3>
                <span style="font-size: 0.88rem; color: var(--text-cream-muted);">No online payment required • Pay when you receive your food</span>
            </div>
        </div>

        <div style="font-size: 0.98rem; color: var(--text-cream-soft); line-height: 1.6; background: var(--bg-surface); padding: 1.3rem 1.6rem; border-radius: var(--radius-lg); border: 1px solid var(--border-subtle);">
            @if($order->fulfillment_type === 'pickup')
                <p style="margin-bottom: 0.6rem;">
                    📍 <strong>Pickup Counter Instructions:</strong> Head to <strong>The Cheddarloin</strong> pickup counter and present your Order Reference: <strong style="color: var(--cheddar-bright); font-size: 1.15rem; font-family: var(--font-heading);">#{{ $order->order_reference }}</strong>.
                </p>
                <p style="margin: 0;">
                    💳 You will pay your total of <strong style="color: var(--gold-bright); font-size: 1.05rem;">${{ number_format($order->total, 2) }}</strong> at the counter using <strong>Cash, Card, or Contactless (Apple/Google Pay)</strong>.
                </p>
            @else
                <p style="margin-bottom: 0.6rem;">
                    🛵 <strong>Delivery Instructions:</strong> When your delivery courier arrives, simply present or state your Order Reference: <strong style="color: var(--cheddar-bright); font-size: 1.15rem; font-family: var(--font-heading);">#{{ $order->order_reference }}</strong>.
                </p>
                <p style="margin: 0;">
                    💵 Hand the exact or approximate cash amount of <strong style="color: var(--gold-bright); font-size: 1.05rem;">${{ number_format($order->total, 2) }}</strong> directly to the delivery courier.
                </p>
            @endif
        </div>
    </div>

    <!-- Order Summary Card -->
    <div style="background: var(--bg-surface); border: 1px solid var(--border-subtle); border-radius: var(--radius-lg); padding: 2rem; text-align: left;">
        <h3 style="font-size: 1.2rem; margin-bottom: 1.2rem; padding-bottom: 0.6rem; border-bottom: 1px solid var(--border-subtle);">
            Receipt Summary
        </h3>

        <div style="display: flex; flex-direction: column; gap: 0.8rem; margin-bottom: 1.5rem;">
            @foreach($order->items as $item)
                <div style="display: flex; justify-content: space-between; font-size: 0.95rem;">
                    <div>
                        <strong>{{ $item->quantity }}x {{ $item->product_name }}</strong>
                        @if($item->selected_options)
                            <div style="font-size: 0.8rem; color: var(--text-cream-muted);">
                                {{ collect($item->selected_options)->pluck('choice')->join(', ') }}
                            </div>
                        @endif
                    </div>
                    <div style="font-weight: 700; color: var(--gold-bright);">${{ number_format($item->subtotal, 2) }}</div>
                </div>
            @endforeach
        </div>

        <div style="border-top: 1px solid var(--border-subtle); padding-top: 1rem; display: flex; flex-direction: column; gap: 0.4rem; font-size: 0.92rem;">
            <div style="display: flex; justify-content: space-between;">
                <span style="color: var(--text-cream-muted);">Subtotal:</span>
                <span>${{ number_format($order->subtotal, 2) }}</span>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span style="color: var(--text-cream-muted);">Fulfillment:</span>
                <span>{{ ucfirst($order->fulfillment_type) }} ({{ $order->delivery_fee > 0 ? '$' . number_format($order->delivery_fee, 2) : 'FREE' }})</span>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span style="color: var(--text-cream-muted);">Tax:</span>
                <span>${{ number_format($order->tax, 2) }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; font-size: 1.25rem; font-weight: 800; color: var(--gold-bright); margin-top: 0.5rem; padding-top: 0.75rem; border-top: 1px solid var(--border-subtle);">
                <span>Total:</span>
                <span>${{ number_format($order->total, 2) }}</span>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Clear client-side cart after successful order placement
    Cart.clear();
});
</script>
@endpush
@endsection
