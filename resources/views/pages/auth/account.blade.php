@extends('layouts.app')

@section('title', 'My Account — The Cheddarain')

@section('content')
<div class="container" style="padding-top: 3.5rem; padding-bottom: 5rem;">
    <div class="section-title">
        <span class="badge badge-cheddar">Customer Portal</span>
        <h2>Welcome, {{ $user->name }}</h2>
        <p>Manage your account details and track your past and active orders.</p>
    </div>

    <div style="display: grid; grid-template-columns: 0.8fr 1.2fr; gap: 2.5rem; align-items: start;">
        <!-- Profile Card -->
        <div style="background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-xl); padding: 2.2rem; box-shadow: var(--shadow-md);">
            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem; padding-bottom: 1.2rem; border-bottom: 1px solid var(--border-subtle);">
                <div style="width: 56px; height: 56px; border-radius: var(--radius-full); background: var(--cheddar-gradient); color: #000; font-size: 1.5rem; font-weight: 800; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 14px var(--cheddar-glow);">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <h3 style="font-size: 1.25rem; color: var(--text-cream); margin-bottom: 0.2rem;">{{ $user->name }}</h3>
                    <span class="badge badge-gold" style="font-size: 0.72rem;">Customer Member</span>
                </div>
            </div>

            <div style="display: flex; flex-direction: column; gap: 1rem; font-size: 0.92rem; margin-bottom: 2rem;">
                <div>
                    <span style="color: var(--text-cream-muted); display: block; font-size: 0.8rem; text-transform: uppercase;">Email Address</span>
                    <strong style="color: var(--text-cream);">{{ $user->email }}</strong>
                </div>

                <div>
                    <span style="color: var(--text-cream-muted); display: block; font-size: 0.8rem; text-transform: uppercase;">Phone Number</span>
                    <strong style="color: var(--text-cream);">{{ $user->phone ?? 'Not provided' }}</strong>
                </div>

                <div>
                    <span style="color: var(--text-cream-muted); display: block; font-size: 0.8rem; text-transform: uppercase;">Member Since</span>
                    <span style="color: var(--text-cream-soft);">{{ $user->created_at->format('F d, Y') }}</span>
                </div>
            </div>

            <div style="display: flex; flex-direction: column; gap: 0.8rem;">
                <a href="{{ route('menu') }}" class="btn btn-primary btn-block">
                    <span>🍔 Order Food Now</span>
                </a>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-secondary btn-block">
                        <span>Sign Out</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Orders & Live Tracking List -->
        <div>
            <div style="background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-xl); padding: 2.2rem; box-shadow: var(--shadow-md);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; padding-bottom: 0.8rem; border-bottom: 1px solid var(--border-subtle);">
                    <h3 style="font-size: 1.25rem; color: var(--text-cream); display: flex; align-items: center; gap: 0.5rem;">
                        <span>📦</span> My Orders & Live Tracking ({{ $orders->count() }})
                    </h3>
                </div>

                @forelse($orders as $order)
                    <div style="background: var(--bg-surface); border: 1px solid var(--border-subtle); border-radius: var(--radius-lg); padding: 1.5rem; margin-bottom: 1.2rem; transition: border-color 0.2s ease;">
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.8rem; flex-wrap: wrap; gap: 0.5rem;">
                            <div>
                                <span style="font-size: 0.8rem; text-transform: uppercase; color: var(--text-cream-muted); letter-spacing: 0.05em;">Order Reference</span>
                                <div style="font-family: var(--font-heading); font-size: 1.35rem; font-weight: 800; color: var(--cheddar-bright);">
                                    #{{ $order->order_reference }}
                                </div>
                                <span style="font-size: 0.82rem; color: var(--text-dim);">{{ $order->created_at->format('M d, Y \a\t h:i A') }} ({{ $order->created_at->diffForHumans() }})</span>
                            </div>

                            <div style="text-align: right;">
                                <span class="badge {{ $order->status === 'delivered' ? 'badge-success' : ($order->status === 'cancelled' ? 'badge-danger' : 'badge-cheddar') }}" style="font-size: 0.8rem;">
                                    {{ $order->status_label }}
                                </span>
                                <div style="font-family: var(--font-heading); font-size: 1.3rem; font-weight: 800; color: var(--gold-bright); margin-top: 0.4rem;">
                                    ${{ number_format($order->total, 2) }}
                                </div>
                            </div>
                        </div>

                        <!-- Item summary -->
                        <div style="font-size: 0.88rem; color: var(--text-cream-muted); margin-bottom: 1.2rem; padding: 0.6rem 0; border-top: 1px dashed var(--border-subtle); border-bottom: 1px dashed var(--border-subtle);">
                            @foreach($order->items as $item)
                                <span>{{ $item->quantity }}x {{ $item->product_name }}@if(!$loop->last), @endif</span>
                            @endforeach
                            <span style="display: block; font-size: 0.8rem; color: var(--text-dim); margin-top: 0.2rem;">Fulfillment: {{ ucfirst($order->fulfillment_type) }}</span>
                        </div>

                        <!-- Action buttons -->
                        <div style="display: flex; gap: 0.8rem; align-items: center; justify-content: flex-end; flex-wrap: wrap;">
                            <a href="{{ $order->whatsapp_link }}" target="_blank" rel="noopener noreferrer" class="btn btn-secondary btn-sm" style="font-size: 0.82rem;">
                                <span>💬 WhatsApp Enquiry</span>
                            </a>
                            <a href="{{ route('order.track.show', $order->order_reference) }}" class="btn btn-primary btn-sm">
                                <span>📍 Track Live Progress &rarr;</span>
                            </a>
                        </div>
                    </div>
                @empty
                    <div style="text-align: center; padding: 3rem 1rem;">
                        <div style="font-size: 3rem; margin-bottom: 1rem;">🍔</div>
                        <h4>No Orders Found</h4>
                        <p style="color: var(--text-cream-muted); font-size: 0.95rem; margin-bottom: 1.5rem;">You haven't placed an order yet. Treat yourself to our double-melted cheddar burgers!</p>
                        <a href="{{ route('menu') }}" class="btn btn-primary">Browse Artisan Menu</a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
