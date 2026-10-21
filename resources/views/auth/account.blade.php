@extends('layouts.app')

@section('title', 'My Account — The Cheddarain')

@section('content')
    <div class="container" style="padding-top: 4rem; padding-bottom: 4rem;">
        <div style="max-width: 980px; margin: 0 auto; display: grid; gap: 2rem;">
            <div style="background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-xl); padding: 2rem; box-shadow: var(--shadow-md);">
                <div style="display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap; margin-bottom: 1.5rem;">
                    <div>
                        <p style="margin: 0; color: var(--text-dim); text-transform: uppercase; letter-spacing: 0.12em; font-size: 0.78rem;">Customer account</p>
                        <h1 style="margin: 0.4rem 0 0; font-size: clamp(2rem, 3vw, 2.6rem);">Hello, {{ $user->name }}!</h1>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary">Log out</button>
                    </form>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem;">
                    <div style="padding: 1rem 1.1rem; background: var(--bg-surface); border: 1px solid var(--border-subtle); border-radius: var(--radius-lg);">
                        <div style="font-size: 0.8rem; color: var(--text-dim); text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 0.35rem;">Name</div>
                        <div style="font-size: 1.05rem; font-weight: 600;">{{ $user->name }}</div>
                    </div>

                    <div style="padding: 1rem 1.1rem; background: var(--bg-surface); border: 1px solid var(--border-subtle); border-radius: var(--radius-lg);">
                        <div style="font-size: 0.8rem; color: var(--text-dim); text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 0.35rem;">Email</div>
                        <div style="font-size: 1.05rem; font-weight: 600;">{{ $user->email }}</div>
                    </div>

                    <div style="padding: 1rem 1.1rem; background: var(--bg-surface); border: 1px solid var(--border-subtle); border-radius: var(--radius-lg);">
                        <div style="font-size: 0.8rem; color: var(--text-dim); text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 0.35rem;">Phone</div>
                        <div style="font-size: 1.05rem; font-weight: 600;">{{ $user->phone ?: 'Not provided' }}</div>
                    </div>
                </div>

                <div style="margin-top: 2rem; display: flex; gap: 1rem; flex-wrap: wrap;">
                    <a href="{{ route('home') }}" class="btn btn-primary">Continue shopping</a>
                    <a href="{{ route('order.track') }}" class="btn btn-secondary">Track an order</a>
                </div>
            </div>

            <div style="background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-xl); padding: 2rem; box-shadow: var(--shadow-md);">
                <div style="display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap; margin-bottom: 1.5rem;">
                    <div>
                        <p style="margin: 0; color: var(--text-dim); text-transform: uppercase; letter-spacing: 0.12em; font-size: 0.78rem;">Recent orders</p>
                        <h2 style="margin: 0.4rem 0 0; font-size: clamp(1.4rem, 2vw, 2rem);">Your order history</h2>
                    </div>
                    <a href="{{ route('order.track') }}" class="btn btn-secondary btn-sm">Track an order</a>
                </div>

                @if($orders->isEmpty())
                    <div style="padding: 1.25rem; border: 1px dashed var(--border-subtle); border-radius: var(--radius-lg); background: var(--bg-surface); text-align: center; color: var(--text-cream-soft);">
                        No orders match this account yet. Place your first order and it will appear here.
                    </div>
                @else
                    <div style="display: grid; gap: 1rem;">
                        @foreach($orders as $order)
                            <div style="padding: 1.1rem 1.2rem; background: var(--bg-surface); border: 1px solid var(--border-subtle); border-radius: var(--radius-lg); display: flex; justify-content: space-between; gap: 1rem; flex-wrap: wrap; align-items: center;">
                                <div>
                                    <div style="font-size: 0.78rem; color: var(--text-dim); text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 0.3rem;">Order #{{ $order->order_reference }}</div>
                                    <div style="font-weight: 700; font-size: 1.08rem;">{{ $order->status_label }}</div>
                                    <div style="color: var(--text-cream-soft); margin-top: 0.2rem;">{{ $order->created_at->format('M j, Y • g:i A') }}</div>
                                </div>
                                <div style="text-align: right;">
                                    <div style="font-size: 0.8rem; color: var(--text-dim); text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 0.25rem;">Total</div>
                                    <div style="font-weight: 700; font-size: 1.1rem;">${{ number_format($order->total, 2) }}</div>
                                    <a href="{{ route('order.track.show', $order->order_reference) }}" style="display: inline-block; margin-top: 0.65rem; color: var(--cheddar-bright); font-weight: 600;">View details →</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
