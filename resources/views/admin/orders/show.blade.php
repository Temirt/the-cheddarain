@extends('layouts.admin')

@section('title', 'Manage Order #' . $order->order_reference . ' — The Cheddarain Admin')

@section('admin_content')
<div>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
        <div>
            <div style="display: flex; align-items: center; gap: 0.8rem; margin-bottom: 0.3rem;">
                <h1 style="font-size: 2rem;">Order #{{ $order->order_reference }}</h1>
                <span class="badge {{ $order->status === 'delivered' ? 'badge-success' : ($order->status === 'cancelled' ? 'badge-danger' : 'badge-cheddar') }}" style="font-size: 0.85rem;">
                    {{ $order->status_label }}
                </span>
            </div>
            <p style="color: var(--text-cream-muted); font-size: 0.95rem;">
                Placed on {{ $order->created_at->format('F d, Y \a\t h:i:s A') }} ({{ $order->created_at->diffForHumans() }})
            </p>
        </div>

        <div style="display: flex; gap: 0.8rem;">
            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary btn-sm">&larr; Back to Orders</a>
            <a href="{{ route('order.track.show', $order->order_reference) }}" target="_blank" class="btn btn-outline-gold btn-sm">
                <span>View Customer Tracking Page ↗</span>
            </a>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1.25fr 0.75fr; gap: 2.5rem; align-items: start;">
        <!-- Left: Status Transition Form & Ordered Items -->
        <div>
            <!-- Status Update Action Card -->
            <div style="background: var(--bg-card); border: 2px solid var(--border-accent); border-radius: var(--radius-lg); padding: 2rem; margin-bottom: 2rem; box-shadow: var(--shadow-md);">
                <h3 style="font-size: 1.25rem; margin-bottom: 1.2rem; color: var(--cheddar-bright); display: flex; align-items: center; gap: 0.5rem;">
                    <span>⚡</span> Update Order Status (Customer Sees This Instantly)
                </h3>

                <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                    @csrf
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.2rem; margin-bottom: 1.2rem;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label" for="status">Next Order Status *</label>
                            <select name="status" id="status" class="form-select" onchange="toggleFormFields(this.value)">
                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending Confirmation</option>
                                <option value="confirmed" {{ $order->status === 'confirmed' ? 'selected' : '' }}>Order Confirmed</option>
                                <option value="preparing" {{ $order->status === 'preparing' ? 'selected' : '' }}>Preparing Food</option>
                                <option value="ready_for_pickup" {{ $order->status === 'ready_for_pickup' ? 'selected' : '' }}>Ready for Pickup</option>
                                <option value="out_for_delivery" {{ $order->status === 'out_for_delivery' ? 'selected' : '' }}>Out for Delivery</option>
                                <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered / Completed</option>
                                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled / Rejected</option>
                            </select>
                        </div>

                        <div class="form-group" style="margin-bottom: 0;" id="prep-time-group">
                            <label class="form-label" for="estimated_prep_minutes">Estimated Prep (Minutes)</label>
                            <input type="number" name="estimated_prep_minutes" id="estimated_prep_minutes" class="form-input" min="1" max="120" value="{{ $order->estimated_prep_minutes ?? 15 }}">
                        </div>
                    </div>

                    <div class="form-group" id="cancellation-group" style="display: {{ $order->status === 'cancelled' ? 'block' : 'none' }}; margin-bottom: 1.2rem;">
                        <label class="form-label" for="cancellation_reason" style="color: var(--danger);">Reason for Cancellation / Rejection *</label>
                        <input type="text" name="cancellation_reason" id="cancellation_reason" class="form-input" placeholder="e.g. Kitchen at maximum capacity, customer phone unreachable" value="{{ $order->cancellation_reason }}">
                    </div>

                    <div class="form-group" style="margin-bottom: 1.5rem;">
                        <label class="form-label" for="note">Status Audit Note (Recorded in History)</label>
                        <input type="text" name="note" id="note" class="form-input" placeholder="e.g. Double cheese melted, dispatched with courier John">
                    </div>

                    <button type="submit" class="btn btn-primary btn-block btn-lg">
                        <span>Save Status & Notify Tracking System &rarr;</span>
                    </button>
                </form>
            </div>

            <!-- Itemized Ordered Products -->
            <div style="background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-lg); padding: 2rem;">
                <h3 style="font-size: 1.2rem; margin-bottom: 1.5rem; padding-bottom: 0.6rem; border-bottom: 1px solid var(--border-subtle);">
                    Ordered Food Items
                </h3>

                <div style="display: flex; flex-direction: column; gap: 1.2rem; margin-bottom: 1.5rem;">
                    @foreach($order->items as $item)
                        <div style="display: flex; justify-content: space-between; align-items: start; padding-bottom: 1rem; border-bottom: 1px solid var(--border-subtle);">
                            <div>
                                <strong style="font-size: 1.1rem; color: var(--text-cream);">{{ $item->quantity }}x {{ $item->product_name }}</strong>
                                <div style="font-size: 0.85rem; color: var(--gold-bright); margin-top: 0.2rem;">
                                    Unit Price: {{ number_format($item->unit_price, 2) }} Birr
                                </div>
                                @if($item->selected_options && count($item->selected_options) > 0)
                                    <div style="margin-top: 0.4rem; display: flex; flex-wrap: wrap; gap: 0.4rem;">
                                        @foreach($item->selected_options as $opt)
                                            <span class="badge badge-gold" style="font-size: 0.72rem;">
                                                {{ $opt['option'] }}: {{ $opt['choice'] }} {{ $opt['price'] > 0 ? '(+' . number_format($opt['price'], 2) . ' Birr)' : '' }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            <div style="font-size: 1.2rem; font-weight: 800; color: var(--text-cream);">
                                {{ number_format($item->subtotal, 2) }} Birr
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Financial Breakdown -->
                <div style="display: flex; flex-direction: column; gap: 0.4rem; font-size: 0.95rem;">
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: var(--text-cream-muted);">Items Subtotal:</span>
                        <span>{{ number_format($order->subtotal, 2) }} Birr</span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: var(--text-cream-muted);">Delivery Fee:</span>
                        <span>{{ number_format($order->delivery_fee, 2) }} Birr</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; font-size: 1.35rem; font-weight: 800; color: var(--gold-bright); padding-top: 0.75rem; border-top: 1px solid var(--border-subtle); margin-top: 0.5rem;">
                        <span>Grand Total:</span>
                        <span>{{ number_format($order->total, 2) }} Birr</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Customer Info & Audit Trail History -->
        <div style="display: flex; flex-direction: column; gap: 2rem;">
            <!-- Customer & Fulfillment Card -->
            <div style="background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-lg); padding: 1.8rem;">
                <h3 style="font-size: 1.15rem; margin-bottom: 1.2rem; padding-bottom: 0.6rem; border-bottom: 1px solid var(--border-subtle);">
                    Customer & Fulfillment
                </h3>

                <div style="display: flex; flex-direction: column; gap: 1rem; font-size: 0.92rem;">
                    <div>
                        <span style="color: var(--text-cream-muted); display: block; font-size: 0.8rem; text-transform: uppercase;">Customer Name</span>
                        <strong style="font-size: 1.05rem;">{{ $order->customer_name }}</strong>
                    </div>

                    <div>
                        <span style="color: var(--text-cream-muted); display: block; font-size: 0.8rem; text-transform: uppercase;">Phone Number</span>
                        <a href="tel:{{ $order->customer_phone }}" style="color: var(--gold-bright); font-weight: 700;">{{ $order->customer_phone }}</a>
                    </div>

                    <div>
                        <span style="color: var(--text-cream-muted); display: block; font-size: 0.8rem; text-transform: uppercase;">Email</span>
                        <span>{{ $order->customer_email }}</span>
                    </div>

                    <div>
                        <span style="color: var(--text-cream-muted); display: block; font-size: 0.8rem; text-transform: uppercase;">Fulfillment Method</span>
                        <span class="badge {{ $order->fulfillment_type === 'pickup' ? 'badge-gold' : 'badge-cheddar' }}">
                            {{ ucfirst($order->fulfillment_type) }}
                        </span>
                    </div>

                    <div>
                        <span style="color: var(--text-cream-muted); display: block; font-size: 0.8rem; text-transform: uppercase;">Payment Details</span>
                        <strong style="color: var(--gold-bright); font-size: 0.95rem;">
                            Pay in Person ({{ ucwords(str_replace('_', ' ', $order->payment_method)) }})
                        </strong>
                        <div style="font-size: 0.82rem; color: var(--text-cream-muted); margin-top: 0.2rem;">
                            Customer brings Order ID <strong>#{{ $order->order_reference }}</strong> to pay ${{ number_format($order->total, 2) }}
                        </div>
                    </div>

                    @if($order->fulfillment_type === 'delivery' && $order->delivery_address)
                        <div style="background: var(--bg-surface); padding: 1rem; border-radius: var(--radius-md); border: 1px solid var(--border-subtle);">
                            <span style="color: var(--gold-bright); display: block; font-size: 0.8rem; text-transform: uppercase; margin-bottom: 0.2rem;">📍 Delivery Address</span>
                            <div>{{ $order->delivery_address }} {{ $order->delivery_apartment ? '(' . $order->delivery_apartment . ')' : '' }}</div>
                            <div style="color: var(--text-cream-muted);">{{ $order->delivery_city }}</div>
                            @if($order->delivery_notes)
                                <div style="font-size: 0.82rem; color: var(--text-dim); margin-top: 0.3rem;">Instructions: {{ $order->delivery_notes }}</div>
                            @endif
                        </div>
                    @endif

                    @if($order->special_instructions)
                        <div style="background: rgba(255, 153, 0, 0.1); border: 1px solid var(--border-accent); padding: 1rem; border-radius: var(--radius-md);">
                            <span style="color: var(--cheddar-bright); display: block; font-size: 0.8rem; text-transform: uppercase; margin-bottom: 0.2rem;">Kitchen Special Note</span>
                            <div style="color: var(--text-cream-soft); font-style: italic;">"{{ $order->special_instructions }}"</div>
                        </div>
                    @endif
                </div>

                <div style="margin-top: 1.5rem; padding-top: 1.2rem; border-top: 1px solid var(--border-subtle);">
                    <a href="{{ $order->whatsapp_link }}" target="_blank" rel="noopener noreferrer" class="btn btn-whatsapp btn-block btn-sm">
                        <span>💬 Message Customer on WhatsApp</span>
                    </a>
                </div>
            </div>

            <!-- Complete Audit Trail Log -->
            <div style="background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-lg); padding: 1.8rem;">
                <h3 style="font-size: 1.15rem; margin-bottom: 1.2rem; padding-bottom: 0.6rem; border-bottom: 1px solid var(--border-subtle);">
                    Status Audit History
                </h3>

                <ul class="audit-history-list">
                    @foreach($order->statusHistories as $hist)
                        <li class="audit-item">
                            <div class="audit-dot"></div>
                            <div class="audit-time">
                                {{ $hist->created_at->format('M d, h:i:s A') }}
                                @if($hist->staff)
                                    • <strong style="color: var(--cheddar-bright);">{{ $hist->staff->name }}</strong>
                                @else
                                    • <em>Customer</em>
                                @endif
                            </div>
                            <div class="audit-note">
                                {{ $hist->note }}
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function toggleFormFields(val) {
    const cancelGroup = document.getElementById('cancellation-group');
    const cancelInput = document.getElementById('cancellation_reason');
    if (val === 'cancelled') {
        cancelGroup.style.display = 'block';
        if (cancelInput) cancelInput.required = true;
    } else {
        cancelGroup.style.display = 'none';
        if (cancelInput) cancelInput.required = false;
    }
}
</script>
@endpush
@endsection
