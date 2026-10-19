@extends('layouts.app')

@section('title', 'Track My Order — The Cheddarain')

@section('content')
<div class="container container-narrow" style="padding-top: 4rem; padding-bottom: 6rem;">
    <div class="section-title">
        <span class="badge badge-cheddar">Real-Time Kitchen Feed</span>
        <h2>Track Your Order</h2>
        <p>Enter your unique order reference and registered phone number to view live progress.</p>
    </div>

    <div style="background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-xl); padding: 3rem 2.5rem; box-shadow: var(--shadow-lg); max-width: 580px; margin: 0 auto;">
        <form action="{{ route('order.track') }}" method="GET">
            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label class="form-label" for="ref">Order Reference Number *</label>
                <input type="text" name="ref" id="ref" class="form-input" style="font-size: 1.2rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;" placeholder="e.g. CHED-782901" required value="{{ request('ref') }}">
                <span class="form-hint">Found on your confirmation screen or receipt.</span>
            </div>

            <div class="form-group" style="margin-bottom: 2rem;">
                <label class="form-label" for="phone">Registered Phone Number</label>
                <input type="tel" name="phone" id="phone" class="form-input" placeholder="e.g. 555-123-4567" value="{{ request('phone') }}">
                <span class="form-hint">Used to verify you are the authorized customer for this order.</span>
            </div>

            <button type="submit" class="btn btn-primary btn-block btn-lg">
                <span>📍</span>
                <span>Track Live Status &rarr;</span>
            </button>
        </form>

        <!-- Demo Order Quick Links for Instant Testing -->
        <div style="margin-top: 2.5rem; padding-top: 1.5rem; border-top: 1px solid var(--border-subtle); text-align: center;">
            <div style="font-size: 0.82rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-cream-muted); margin-bottom: 0.8rem;">
                Try Demo Orders (Test the Pipeline)
            </div>
            <div style="display: flex; gap: 0.6rem; justify-content: center; flex-wrap: wrap;">
                <a href="{{ route('order.track.show', ['reference' => 'CHED-782901', 'phone' => '5551234567']) }}" class="badge badge-cheddar" style="text-decoration: none; padding: 0.5rem 0.9rem;">
                    CHED-782901 (Preparing)
                </a>
                <a href="{{ route('order.track.show', ['reference' => 'CHED-782902', 'phone' => '5551234567']) }}" class="badge badge-gold" style="text-decoration: none; padding: 0.5rem 0.9rem;">
                    CHED-782902 (Ready for Pickup)
                </a>
                <a href="{{ route('order.track.show', ['reference' => 'CHED-782904', 'phone' => '5551234567']) }}" class="badge badge-success" style="text-decoration: none; padding: 0.5rem 0.9rem;">
                    CHED-782904 (Delivered)
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
