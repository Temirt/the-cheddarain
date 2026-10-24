@extends('layouts.admin')

@section('title', 'Manage Orders — The Cheddarloin Admin')

@section('admin_content')
<div>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h1 style="font-size: 2rem; margin-bottom: 0.3rem;">Order Management</h1>
            <p style="color: var(--text-cream-muted); font-size: 0.95rem;">Filter, search, inspect, and update customer orders.</p>
        </div>
    </div>

    <!-- Filters & Search Form -->
    <div style="background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-lg); padding: 1.5rem; margin-bottom: 2rem;">
        <form action="{{ route('admin.orders.index') }}" method="GET" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)) 100px; gap: 1rem; align-items: end;">
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label" for="search">Search</label>
                <input type="text" name="search" id="search" class="form-input" placeholder="Ref, Name, Phone..." value="{{ request('search') }}">
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label" for="status">Status</label>
                <select name="status" id="status" class="form-select">
                    @foreach($statuses as $value => $label)
                        <option value="{{ $value }}" {{ request('status') === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label" for="date">Order Date</label>
                <input type="date" name="date" id="date" class="form-input" value="{{ request('date') }}">
            </div>

            <div>
                <button type="submit" class="btn btn-primary btn-block" style="padding: 0.85rem;">
                    <span>Filter</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="data-table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Ref #</th>
                    <th>Customer</th>
                    <th>Fulfillment</th>
                    <th>Items</th>
                    <th>Total</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th>Date & Time</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td>
                            <strong style="color: var(--cheddar-bright); font-family: var(--font-heading);">
                                #{{ $order->order_reference }}
                            </strong>
                        </td>
                        <td>
                            <div style="font-weight: 600;">{{ $order->customer_name }}</div>
                            <div style="font-size: 0.78rem; color: var(--text-cream-muted);">📞 {{ $order->customer_phone }}</div>
                        </td>
                        <td>
                            <span class="badge {{ $order->fulfillment_type === 'pickup' ? 'badge-gold' : 'badge-cheddar' }}">
                                {{ ucfirst($order->fulfillment_type) }}
                            </span>
                        </td>
                        <td style="font-size: 0.88rem;">{{ $order->items->sum('quantity') }} items</td>
                        <td style="font-weight: 700; color: var(--gold-bright);">{{ number_format($order->total, 2) }} Birr</td>
                        <td>
                            <span style="font-size: 0.82rem; text-transform: uppercase; color: {{ $order->payment_status === 'paid' ? 'var(--success)' : 'var(--warning)' }};">
                                {{ $order->payment_method }} ({{ $order->payment_status }})
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $order->status === 'delivered' ? 'badge-success' : ($order->status === 'cancelled' ? 'badge-danger' : 'badge-cheddar') }}">
                                {{ $order->status_label }}
                            </span>
                        </td>
                        <td style="font-size: 0.85rem; color: var(--text-cream-muted);">
                            {{ $order->created_at->format('M d, Y') }}<br>
                            <span style="font-size: 0.78rem; color: var(--text-dim);">{{ $order->created_at->format('h:i A') }}</span>
                        </td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-secondary btn-sm">
                                Manage &rarr;
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" style="text-align: center; padding: 3rem; color: var(--text-cream-muted);">
                            No orders found matching the selected criteria.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 1.5rem;">
        {{ $orders->links() }}
    </div>
</div>
@endsection
