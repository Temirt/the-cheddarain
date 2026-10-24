@extends('layouts.app')

@section('title', 'My Orders & Order Tracker — The Cheddarloin')

@section('content')
<div class="container container-narrow" style="padding-top: 4rem; padding-bottom: 6rem;">
    <div class="section-title" style="text-align: center; margin-bottom: 2.5rem;">
        <span class="badge badge-gold" style="margin-bottom: 0.5rem;">5-Stage Live Status Feed</span>
        <h1 style="font-family: var(--font-heading); font-size: 2.4rem; color: var(--gold-primary); margin-bottom: 0.5rem;">My Orders & Tracking</h1>
        <p style="color: var(--text-secondary); max-width: 540px; margin: 0 auto;">
            Follow your order from kitchen prep to doorstep arrival in real time.
        </p>
    </div>

    <!-- Dynamic "My Recent Orders" Section (Populated via Vanilla JS from localStorage) -->
    <div id="my-recent-orders-container" style="display: none; margin-bottom: 3rem;">
        <h3 style="font-size: 1.25rem; color: var(--text-cream); margin-bottom: 1rem; display: flex; align-items: center; gap: 0.6rem;">
            <span>📦</span> Your Recent Orders
        </h3>
        <div id="my-orders-list" style="display: flex; flex-direction: column; gap: 1rem;">
            <!-- Rendered by JavaScript -->
        </div>
    </div>

    <!-- Search / Lookup Card -->
    <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-xl); padding: 2.5rem; box-shadow: var(--shadow-lg); max-width: 580px; margin: 0 auto;">
        <h3 style="font-size: 1.25rem; color: var(--text-cream); margin-bottom: 1.2rem; display: flex; align-items: center; gap: 0.5rem;">
            <span>🔍</span> Look Up Any Order
        </h3>

        <form action="{{ route('order.track') }}" method="GET">
            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label class="form-label" for="ref">Order Reference *</label>
                <input type="text" name="ref" id="ref" class="form-input" style="font-size: 1.15rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;" placeholder="e.g. CHED-782901" required value="{{ request('ref') }}">
                <span class="form-hint" style="color: var(--text-muted); font-size: 0.8rem;">Provided upon checkout and confirmation.</span>
            </div>

            <div class="form-group" style="margin-bottom: 2rem;">
                <label class="form-label" for="phone">Registered Phone Number</label>
                <input type="tel" name="phone" id="phone" class="form-input" placeholder="e.g. +251 911 234 567" value="{{ request('phone') }}">
                <span class="form-hint" style="color: var(--text-muted); font-size: 0.8rem;">Used to verify ownership of the ticket.</span>
            </div>

            <button type="submit" class="btn btn-primary btn-block btn-lg" style="border-radius: var(--radius-full);">
                <span>📍</span>
                <span>Track Live Progress &rarr;</span>
            </button>
        </form>

        <!-- Demo Order Quick Links for Instant Testing -->
        <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--border-color); text-align: center;">
            <div style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted); margin-bottom: 0.8rem;">
                Sample Demo Orders (Instant Review)
            </div>
            <div style="display: flex; gap: 0.6rem; justify-content: center; flex-wrap: wrap;">
                <a href="{{ route('orders.show', ['reference' => 'CHED-782901', 'phone' => '5551234567']) }}" class="badge badge-cheddar" style="text-decoration: none; padding: 0.5rem 0.9rem;">
                    CHED-782901 (Preparing)
                </a>
                <a href="{{ route('orders.show', ['reference' => 'CHED-782902', 'phone' => '5551234567']) }}" class="badge badge-gold" style="text-decoration: none; padding: 0.5rem 0.9rem;">
                    CHED-782902 (Ready for Pickup)
                </a>
                <a href="{{ route('orders.show', ['reference' => 'CHED-782904', 'phone' => '5551234567']) }}" class="badge badge-success" style="text-decoration: none; padding: 0.5rem 0.9rem;">
                    CHED-782904 (Delivered)
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Render recent orders stored on this device in localStorage
    const container = document.getElementById('my-recent-orders-container');
    const list = document.getElementById('my-orders-list');
    const orders = Cart.getMyOrders();

    if (orders && orders.length > 0) {
        container.style.display = 'block';
        let html = '';
        orders.forEach(order => {
            const ref = typeof order === 'string' ? order : order.reference;
            const date = order.date ? order.date : '';
            const total = order.total ? order.total : '';
            const status = order.status ? order.status.toUpperCase() : 'IN PROGRESS';

            html += `
                <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-lg); padding: 1.4rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
                    <div>
                        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.3rem;">
                            <strong style="font-family: var(--font-heading); font-size: 1.25rem; color: var(--gold-primary);">#${ref}</strong>
                            <span class="badge badge-gold" style="font-size: 0.72rem;">${status}</span>
                        </div>
                        <div style="font-size: 0.85rem; color: var(--text-secondary);">
                            ${date ? `<span>Placed: ${date}</span> • ` : ''}
                            ${total ? `<strong>${total}</strong>` : ''}
                        </div>
                    </div>
                    <a href="/orders/${ref}" class="btn btn-primary btn-sm" style="border-radius: var(--radius-full);">
                        <span>Live Status</span>
                        <span>&rarr;</span>
                    </a>
                </div>
            `;
        });
        list.innerHTML = html;
    }
});
</script>
@endpush
@endsection
