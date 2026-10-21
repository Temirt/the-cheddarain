@extends('layouts.app')

@section('title', 'Track Order #' . $order->order_reference . ' — The Cheddarain')

@section('content')
<div class="container" style="padding-top: 3.5rem; padding-bottom: 5rem;">

    @if(!$isVerified)
        <!-- Security Verification Step -->
        <div class="container-narrow" style="margin: 0 auto;">
            <div class="section-title">
                <span class="badge badge-warning">Customer Verification Required</span>
                <h2>Verify Your Order Access</h2>
                <p>For your privacy and security, please enter the phone number associated with order <strong>#{{ $order->order_reference }}</strong>.</p>
            </div>

            @if(isset($error))
                <div class="alert alert-danger" style="max-width: 520px; margin: 0 auto 1.5rem auto;">
                    <span>✕</span>
                    <span>{{ $error }}</span>
                </div>
            @endif

            <div style="background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-xl); padding: 2.5rem; max-width: 520px; margin: 0 auto; box-shadow: var(--shadow-lg);">
                <form action="{{ route('order.track.verify', $order->order_reference) }}" method="POST">
                    @csrf
                    <div class="form-group" style="margin-bottom: 1.5rem;">
                        <label class="form-label" for="verify_phone">Registered Phone Number</label>
                        <input type="tel" name="phone" id="verify_phone" class="form-input" placeholder="e.g. 555-123-4567 or last 4 digits" required autofocus>
                        <span class="form-hint">Enter the phone number you entered during checkout.</span>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block btn-lg">
                        <span>Unlock Live Tracking Feed &rarr;</span>
                    </button>
                </form>

                <div style="text-align: center; margin-top: 1.5rem;">
                    <a href="{{ route('order.track') }}" style="font-size: 0.88rem; color: var(--text-cream-muted);">&larr; Look up a different order</a>
                </div>
            </div>
        </div>

    @else
        <!-- Full Verified McDonald's-Style Live Order Tracker -->
        <div class="order-tracker-card">
            <!-- Tracker Header -->
            <div class="tracker-header">
                <div class="tracker-ref-box">
                    <span class="tracker-ref-label">Live Order Tracking</span>
                    <span class="tracker-ref-number">#{{ $order->order_reference }}</span>
                    <span style="font-size: 0.85rem; color: var(--text-cream-muted);">
                        Placed on {{ $order->created_at->format('M d, Y \a\t h:i A') }} • Customer: <strong>{{ $order->customer_name }}</strong>
                    </span>
                </div>

                <div style="display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;">
                    <div class="live-poll-indicator" id="live-poll-indicator">
                        <div class="pulse-dot"></div>
                        <span>Live Tracking Active</span>
                    </div>

                    <span style="font-size: 0.82rem; color: var(--text-cream-muted);" id="tracker-last-updated">
                        Updated {{ $order->updated_at->diffForHumans() }}
                    </span>
                </div>
            </div>

            <!-- McDonald's 5-Step Visual Timeline -->
            @php
                $step = $order->timeline_step;
                $stepPercentages = [1 => 15, 2 => 35, 3 => 65, 4 => 85, 5 => 100, 0 => 0];
                $percent = $stepPercentages[$step] ?? 15;
            @endphp

            <div class="timeline-track-wrapper">
                <div class="timeline-progress-bar">
                    <div class="timeline-progress-fill" id="timeline-progress-fill" style="width: {{ $percent }}%;"></div>
                </div>

                <ul class="timeline-steps">
                    <!-- Step 1: Order Placed -->
                    <li class="timeline-step {{ $step > 1 ? 'completed' : ($step === 1 ? 'active' : '') }}" id="timeline-step-1">
                        <div class="step-circle">
                            @if($step > 1) ✓ @else 📝 @endif
                        </div>
                        <div class="step-label">Order Placed</div>
                        <div class="step-subtext">Received</div>
                    </li>

                    <!-- Step 2: Order Confirmed -->
                    <li class="timeline-step {{ $step > 2 ? 'completed' : ($step === 2 ? 'active' : '') }}" id="timeline-step-2">
                        <div class="step-circle">
                            @if($step > 2) ✓ @else 👨‍🍳 @endif
                        </div>
                        <div class="step-label">Order Confirmed</div>
                        <div class="step-subtext">Kitchen Accepted</div>
                    </li>

                    <!-- Step 3: Preparing Food -->
                    <li class="timeline-step {{ $step > 3 ? 'completed' : ($step === 3 ? 'active' : '') }}" id="timeline-step-3">
                        <div class="step-circle">
                            @if($step > 3) ✓ @else 🍔 @endif
                        </div>
                        <div class="step-label">Preparing Food</div>
                        <div class="step-subtext">Smashing & Melting</div>
                    </li>

                    <!-- Step 4: Ready for Pickup or Out for Delivery -->
                    <li class="timeline-step {{ $step > 4 ? 'completed' : ($step === 4 ? 'active' : '') }}" id="timeline-step-4">
                        <div class="step-circle">
                            @if($step > 4) ✓ @else {{ $order->fulfillment_type === 'pickup' ? '🛍️' : '🛵' }} @endif
                        </div>
                        <div class="step-label" id="step-4-label">
                            {{ $order->fulfillment_type === 'pickup' ? 'Ready for Pickup' : 'Out for Delivery' }}
                        </div>
                        <div class="step-subtext">Hot & Fresh</div>
                    </li>

                    <!-- Step 5: Delivered / Completed -->
                    <li class="timeline-step {{ $step === 5 ? 'completed active' : '' }}" id="timeline-step-5">
                        <div class="step-circle">
                            🎉
                        </div>
                        <div class="step-label">Delivered</div>
                        <div class="step-subtext">Enjoy Your Meal</div>
                    </li>
                </ul>
            </div>

            <!-- Status Headline & Countdown Banner -->
            <div class="tracker-status-banner">
                <div class="status-headline">
                    <div class="status-icon-large">
                        @if($order->status === 'cancelled')
                            ❌
                        @elseif($order->status === 'delivered')
                            🎉
                        @elseif($order->status === 'ready_for_pickup')
                            🛍️
                        @elseif($order->status === 'out_for_delivery')
                            🛵
                        @elseif($order->status === 'preparing')
                            🍔
                        @else
                            👨‍🍳
                        @endif
                    </div>
                    <div class="status-headline-text">
                        <h3 id="tracker-status-headline">{{ $order->status_label }}</h3>
                        <p id="tracker-status-desc">
                            @if($order->status === 'pending')
                                We have received your order. The kitchen will review and confirm shortly.
                            @elseif($order->status === 'confirmed')
                                Your order is confirmed! Chefs are queuing up your artisanal cheddar feast.
                            @elseif($order->status === 'preparing')
                                Cooking fresh right now! Patties sizzling and cheddar lava dripping.
                            @elseif($order->status === 'ready_for_pickup')
                                Your order is bagged and waiting hot at the counter. Show order #{{ $order->order_reference }} at pickup!
                            @elseif($order->status === 'out_for_delivery')
                                Our delivery driver is en route with your piping hot order!
                            @elseif($order->status === 'delivered')
                                Delivered! We hope you loved every bite of The Cheddarain experience.
                            @elseif($order->status === 'cancelled')
                                Order was cancelled. Reason: {{ $order->cancellation_reason ?? 'Restaurant closed/capacity' }}. Contact support below.
                            @endif
                        </p>
                    </div>
                </div>

                <div class="tracker-time-countdown">
                    <div class="countdown-label">Estimated Wait</div>
                    <div class="countdown-value" id="tracker-countdown-value">
                        @if($order->status === 'preparing' && $order->estimated_prep_minutes)
                            ~{{ $order->estimated_prep_minutes }} mins
                        @elseif($order->status === 'ready_for_pickup')
                            READY NOW
                        @elseif($order->status === 'out_for_delivery')
                            ON THE WAY
                        @elseif($order->status === 'delivered')
                            COMPLETED
                        @elseif($order->status === 'cancelled')
                            CANCELLED
                        @else
                            12-15 mins
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Summary & Audit History Grid -->
        <div class="tracker-details-grid">
            <!-- Left Panel: Itemized Order Details -->
            <div class="tracker-card-panel">
                <h3><span>🧾</span> Order Breakdown</h3>

                <div style="display: flex; flex-direction: column; gap: 1rem; margin-bottom: 1.5rem;">
                    @foreach($order->items as $item)
                        <div style="display: flex; justify-content: space-between; align-items: start; padding-bottom: 0.8rem; border-bottom: 1px solid var(--border-subtle);">
                            <div>
                                <strong style="color: var(--text-cream); font-size: 1.05rem;">{{ $item->quantity }}x {{ $item->product_name }}</strong>
                                @if($item->selected_options && count($item->selected_options) > 0)
                                    <div style="font-size: 0.85rem; color: var(--text-cream-muted); margin-top: 0.2rem;">
                                        @foreach($item->selected_options as $opt)
                                            <span class="badge badge-gold" style="font-size: 0.7rem; margin-right: 0.3rem;">
                                                {{ $opt['choice'] }} {{ $opt['price'] > 0 ? '(+$' . number_format($opt['price'], 2) . ')' : '' }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            <div style="font-weight: 700; color: var(--gold-bright); font-size: 1.1rem;">
                                ${{ number_format($item->subtotal, 2) }}
                            </div>
                        </div>
                    @endforeach
                </div>

                <div style="display: flex; flex-direction: column; gap: 0.5rem; font-size: 0.95rem; margin-bottom: 1.5rem;">
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: var(--text-cream-muted);">Subtotal</span>
                        <span>${{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: var(--text-cream-muted);">Fulfillment</span>
                        <span>{{ ucfirst($order->fulfillment_type) }} ({{ $order->delivery_fee > 0 ? '$' . number_format($order->delivery_fee, 2) : 'FREE' }})</span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: var(--text-cream-muted);">Tax</span>
                        <span>${{ number_format($order->tax, 2) }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; font-size: 1.35rem; font-weight: 800; color: var(--gold-bright); padding-top: 0.75rem; border-top: 1px solid var(--border-subtle); margin-top: 0.5rem;">
                        <span>Total Due</span>
                        <span>${{ number_format($order->total, 2) }}</span>
                    </div>
                </div>

                <!-- In-Person Payment Reminder Card -->
                <div style="background: rgba(255, 153, 0, 0.1); border: 1px dashed var(--cheddar-primary); border-radius: var(--radius-md); padding: 1.1rem 1.3rem; margin-bottom: 1.5rem;">
                    <div style="display: flex; align-items: center; gap: 0.5rem; color: var(--gold-bright); font-weight: 700; font-size: 0.95rem; margin-bottom: 0.4rem;">
                        <span>💵</span> Pay in Person With Order ID
                    </div>
                    <p style="font-size: 0.88rem; color: var(--text-cream-soft); margin: 0; line-height: 1.5;">
                        @if($order->fulfillment_type === 'pickup')
                            Bring your Order Reference <strong style="color: var(--cheddar-bright);">#{{ $order->order_reference }}</strong> to the pickup counter to pay ${{ number_format($order->total, 2) }} via Cash, Card, or Contactless.
                        @else
                            Present your Order Reference <strong style="color: var(--cheddar-bright);">#{{ $order->order_reference }}</strong> to your delivery courier to pay ${{ number_format($order->total, 2) }} in cash upon arrival.
                        @endif
                    </p>
                </div>

                @if($order->fulfillment_type === 'delivery' && $order->delivery_address)
                    <div style="background: var(--bg-surface); padding: 1.2rem; border-radius: var(--radius-md); border: 1px solid var(--border-subtle); margin-top: 1.5rem;">
                        <strong style="display: block; font-size: 0.85rem; color: var(--gold-bright); text-transform: uppercase; margin-bottom: 0.3rem;">📍 Delivery Destination</strong>
                        <div style="font-size: 0.95rem;">{{ $order->delivery_address }} {{ $order->delivery_apartment ? '(' . $order->delivery_apartment . ')' : '' }}</div>
                        <div style="font-size: 0.85rem; color: var(--text-cream-muted);">{{ $order->delivery_city }}</div>
                        @if($order->delivery_notes)
                            <div style="font-size: 0.82rem; color: var(--text-dim); margin-top: 0.4rem;">Note: {{ $order->delivery_notes }}</div>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Right Panel: Live Kitchen Audit Timeline & Support -->
            <div style="display: flex; flex-direction: column; gap: 2rem;">
                <!-- Live Kitchen History -->
                <div class="tracker-card-panel">
                    <h3><span>⏱️</span> Kitchen Progress Log</h3>
                    <ul class="audit-history-list" id="tracker-audit-history">
                        @foreach($order->statusHistories as $history)
                            <li class="audit-item">
                                <div class="audit-dot"></div>
                                <div class="audit-time">
                                    {{ $history->created_at->format('h:i A') }}
                                    @if($history->staff)
                                        • {{ $history->staff->name }}
                                    @endif
                                </div>
                                <div class="audit-note">
                                    {{ $history->note ?? 'Order updated to ' . ucfirst($history->to_status) }}
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- WhatsApp Support Card -->
                <div class="tracker-card-panel" style="background: radial-gradient(circle at 100% 0%, rgba(37, 211, 102, 0.15) 0%, var(--bg-card) 70%);">
                    <h3><span>💬</span> Need Assistance?</h3>
                    <p style="font-size: 0.92rem; color: var(--text-cream-soft); margin-bottom: 1.2rem;">
                        Have questions about your order or special dietary requirements? Reach our dispatch team directly on WhatsApp.
                    </p>
                    <a href="{{ $order->whatsapp_link }}" target="_blank" rel="noopener noreferrer" class="btn btn-whatsapp btn-block">
                        <span>💬 Message Support on WhatsApp</span>
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>

@if($isVerified)
@push('scripts')
<script src="{{ asset('js/tracker.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Initialize McDonald's real-time tracker auto-polling
    new OrderTracker(
        "{{ $order->order_reference }}",
        "{{ $order->status }}",
        {{ $order->timeline_step }}
    );
});
</script>
@endpush
@endif
@endsection
