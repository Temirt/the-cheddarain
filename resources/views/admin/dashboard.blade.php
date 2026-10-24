@extends('layouts.admin')

@section('title', 'Store Dashboard — The Cheddarloin Admin')

@section('admin_content')
<div>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h1 style="font-size: 2rem; margin-bottom: 0.3rem;">Operational Overview</h1>
            <p style="color: var(--text-cream-muted); font-size: 0.95rem;">Real-time restaurant metrics, kitchen queues, and quick status controls.</p>
        </div>

        <div style="display: flex; gap: 0.8rem;">
            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary btn-sm">
                <span>View All Orders</span>
            </a>
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">
                <span>+ Add Menu Item</span>
            </a>
        </div>
    </div>

    <!-- Metrics Grid -->
    <div class="metrics-grid">
        <div class="metric-card">
            <div class="metric-title">Today's Orders</div>
            <div class="metric-value">{{ $metrics['today_orders'] }}</div>
        </div>

        <div class="metric-card">
            <div class="metric-title">Today's Revenue</div>
            <div class="metric-value" style="color: var(--gold-bright);">{{ number_format($metrics['today_revenue'], 2) }} Birr</div>
        </div>

        <div class="metric-card">
            <div class="metric-title">Pending Confirmation</div>
            <div class="metric-value" style="color: var(--warning);">{{ $metrics['pending_orders'] }}</div>
        </div>

        <div class="metric-card">
            <div class="metric-title">Currently Preparing</div>
            <div class="metric-value" style="color: var(--cheddar-bright);">{{ $metrics['preparing_orders'] }}</div>
        </div>

        <div class="metric-card">
            <div class="metric-title">Ready / Out for Delivery</div>
            <div class="metric-value" style="color: #60a5fa;">{{ $metrics['ready_orders'] }}</div>
        </div>

        <div class="metric-card">
            <div class="metric-title">Completed Today</div>
            <div class="metric-value" style="color: var(--success);">{{ $metrics['completed_orders'] }}</div>
        </div>
    </div>

    <!-- Active Kitchen Orders Board -->
    <div style="margin-bottom: 3rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.2rem;">
            <h2 style="font-size: 1.35rem; display: flex; align-items: center; gap: 0.5rem;">
                <span>🔥</span> Active Kitchen Pipeline ({{ $activeKitchenOrders->count() }})
            </h2>
            <span style="font-size: 0.82rem; color: var(--text-cream-muted);">Auto-sorted by arrival time</span>
        </div>

        @if($activeKitchenOrders->count() > 0)
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1.5rem;">
                @foreach($activeKitchenOrders as $order)
                    <div style="background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-lg); padding: 1.6rem; display: flex; flex-direction: column;">
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                            <div>
                                <a href="{{ route('admin.orders.show', $order->id) }}" style="font-family: var(--font-heading); font-size: 1.25rem; font-weight: 800; color: var(--cheddar-bright);">
                                    #{{ $order->order_reference }}
                                </a>
                                <div style="font-size: 0.85rem; color: var(--text-cream-muted);">
                                    {{ $order->customer_name }} • {{ $order->created_at->diffForHumans() }}
                                </div>
                            </div>
                            <span class="badge {{ $order->status === 'preparing' ? 'badge-cheddar' : ($order->status === 'pending' ? 'badge-warning' : 'badge-gold') }}">
                                {{ $order->status_label }}
                            </span>
                        </div>

                        <div style="font-size: 0.88rem; margin-bottom: 1rem; color: var(--text-cream-soft); flex: 1;">
                            <div style="font-weight: 600; color: var(--text-cream-muted); margin-bottom: 0.4rem; text-transform: uppercase; font-size: 0.75rem;">Items:</div>
                            @foreach($order->items as $item)
                                <div style="margin-bottom: 0.2rem;">
                                    <strong>{{ $item->quantity }}x</strong> {{ $item->product_name }}
                                </div>
                            @endforeach
                            @if($order->special_instructions)
                                <div style="margin-top: 0.6rem; font-size: 0.8rem; color: var(--gold-bright); font-style: italic;">
                                    Note: "{{ $order->special_instructions }}"
                                </div>
                            @endif
                        </div>

                        <div style="display: flex; gap: 0.6rem; margin-top: auto; padding-top: 1rem; border-top: 1px solid var(--border-subtle);">
                            @if($order->status === 'pending')
                                <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" style="flex: 1;">
                                    @csrf
                                    <input type="hidden" name="status" value="confirmed">
                                    <input type="hidden" name="note" value="Order confirmed by staff.">
                                    <button type="submit" class="btn btn-primary btn-sm btn-block">Confirm Order</button>
                                </form>
                            @elseif($order->status === 'confirmed')
                                <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" style="flex: 1;">
                                    @csrf
                                    <input type="hidden" name="status" value="preparing">
                                    <input type="hidden" name="estimated_prep_minutes" value="15">
                                    <input type="hidden" name="note" value="Chef started grilling Angus patties.">
                                    <button type="submit" class="btn btn-primary btn-sm btn-block">Start Prep (15m)</button>
                                </form>
                            @elseif($order->status === 'preparing')
                                <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" style="flex: 1;">
                                    @csrf
                                    <input type="hidden" name="status" value="{{ $order->fulfillment_type === 'pickup' ? 'ready_for_pickup' : 'out_for_delivery' }}">
                                    <input type="hidden" name="note" value="Bagged and ready for {{ $order->fulfillment_type }}.">
                                    <button type="submit" class="btn btn-primary btn-sm btn-block">
                                        {{ $order->fulfillment_type === 'pickup' ? 'Mark Ready for Pickup' : 'Dispatch for Delivery' }}
                                    </button>
                                </form>
                            @elseif(in_array($order->status, ['ready_for_pickup', 'out_for_delivery']))
                                <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" style="flex: 1;">
                                    @csrf
                                    <input type="hidden" name="status" value="delivered">
                                    <input type="hidden" name="note" value="Order handed over to customer. Completed.">
                                    <button type="submit" class="btn btn-primary btn-sm btn-block" style="background: var(--success); color: #fff;">
                                        Mark Delivered / Complete
                                    </button>
                                </form>
                            @endif

                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-secondary btn-sm" title="View Full Details">
                                <span>👁️</span>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div style="background: var(--bg-card); padding: 3rem; border-radius: var(--radius-lg); text-align: center; border: 1px solid var(--border-subtle);">
                <div style="font-size: 2.5rem; margin-bottom: 0.8rem;">🍳</div>
                <h3>All Orders Cleared!</h3>
                <p style="color: var(--text-cream-muted);">The kitchen queue is currently caught up. New website orders will appear here automatically.</p>
            </div>
        @endif
    </div>

    <!-- Recent Orders List -->
    <div>
        <h2 style="font-size: 1.35rem; margin-bottom: 1.2rem;">Recent Orders</h2>
        <div class="data-table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Reference</th>
                        <th>Customer</th>
                        <th>Type</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentOrders as $order)
                        <tr>
                            <td>
                                <strong style="color: var(--cheddar-bright);">#{{ $order->order_reference }}</strong>
                            </td>
                            <td>
                                <div>{{ $order->customer_name }}</div>
                                <div style="font-size: 0.78rem; color: var(--text-cream-muted);">{{ $order->customer_phone }}</div>
                            </td>
                            <td>
                                <span class="badge {{ $order->fulfillment_type === 'pickup' ? 'badge-gold' : 'badge-cheddar' }}" style="font-size: 0.75rem;">
                                    {{ ucfirst($order->fulfillment_type) }}
                                </span>
                            </td>
                            <td style="font-weight: 700; color: var(--gold-bright);">{{ number_format($order->total, 2) }} Birr</td>
                            <td>
                                <span class="badge {{ $order->status === 'delivered' ? 'badge-success' : ($order->status === 'cancelled' ? 'badge-danger' : 'badge-cheddar') }}" style="font-size: 0.75rem;">
                                    {{ $order->status_label }}
                                </span>
                            </td>
                            <td style="font-size: 0.85rem; color: var(--text-cream-muted);">{{ $order->created_at->diffForHumans() }}</td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-secondary btn-sm" style="padding: 0.35rem 0.75rem;">
                                    Manage &rarr;
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
