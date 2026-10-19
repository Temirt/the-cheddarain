@extends('layouts.app')

@section('title', 'About Us — The Cheddarain')

@section('content')
<div class="container container-narrow" style="padding-top: 4rem; padding-bottom: 6rem;">
    <div class="section-title">
        <span class="badge badge-cheddar">The Cheddar Obsession</span>
        <h2>Crafted with Melted Gold</h2>
        <p>Born from an uncompromising belief that a great burger is defined by two things: an intense smash crust and a river of real aged cheddar.</p>
    </div>

    <div style="background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-xl); overflow: hidden; margin-bottom: 3.5rem; box-shadow: var(--shadow-lg);">
        <img src="https://images.unsplash.com/photo-1550547660-d9450f859349?auto=format&fit=crop&w=1200&q=80" alt="Kitchen Prep" style="width: 100%; height: 380px; object-fit: cover;">
        <div style="padding: 2.5rem;">
            <h3 style="font-size: 1.6rem; margin-bottom: 1rem; color: var(--gold-bright);">Our Culinary Philosophy</h3>
            <p style="color: var(--text-cream-soft); font-size: 1.05rem; line-height: 1.7; margin-bottom: 1.5rem;">
                At <strong>The Cheddarain</strong>, we don't believe in dry, lukewarm slices of processed cheese. Every single burger is crowned with our proprietary Wisconsin sharp cheddar melt, prepared fresh every morning with cream, roasted aromatics, and a touch of secret spice.
            </p>
            <p style="color: var(--text-cream-muted); font-size: 1rem; line-height: 1.7;">
                Our beef is 100% prime Angus chuck and brisket, ground daily and never frozen. Smashed hard on our seasoned flat-tops at 450°F, we lock in rich caramelization and deep savory juices before enveloping the patty in warm brioche.
            </p>
        </div>
    </div>

    <!-- 3 Core Pillars -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem; margin-bottom: 3.5rem;">
        <div style="background: var(--bg-surface); padding: 2rem; border-radius: var(--radius-lg); border: 1px solid var(--border-subtle);">
            <div style="font-size: 2.2rem; margin-bottom: 0.8rem;">🥩</div>
            <h4 style="margin-bottom: 0.5rem;">100% Prime Angus</h4>
            <p style="font-size: 0.9rem; color: var(--text-cream-muted);">Freshly coarse-ground chuck, brisket, and short rib blend. Never frozen, no fillers.</p>
        </div>

        <div style="background: var(--bg-surface); padding: 2rem; border-radius: var(--radius-lg); border: 1px solid var(--border-subtle);">
            <div style="font-size: 2.2rem; margin-bottom: 0.8rem;">🧀</div>
            <h4 style="margin-bottom: 0.5rem;">Real Wisconsin Cheddar</h4>
            <p style="font-size: 0.9rem; color: var(--text-cream-muted);">Aged sharp cheddar melted into silky liquid gold, cascading over every single burger.</p>
        </div>

        <div style="background: var(--bg-surface); padding: 2rem; border-radius: var(--radius-lg); border: 1px solid var(--border-subtle);">
            <div style="font-size: 2.2rem; margin-bottom: 0.8rem;">🍟</div>
            <h4 style="margin-bottom: 0.5rem;">Double-Fried Russets</h4>
            <p style="font-size: 0.9rem; color: var(--text-cream-muted);">Hand-cut Idaho russet potatoes blanched and twice-fried for ultimate crunch.</p>
        </div>
    </div>

    <div style="text-align: center; background: radial-gradient(circle, rgba(255, 153, 0, 0.12) 0%, transparent 70%); padding: 3rem; border-radius: var(--radius-xl); border: 1px solid var(--border-accent);">
        <h3 style="margin-bottom: 1rem;">Hungry Yet?</h3>
        <p style="color: var(--text-cream-muted); margin-bottom: 1.8rem;">Experience what true melted perfection tastes like.</p>
        <a href="{{ route('menu') }}" class="btn btn-primary btn-lg">Browse Our Menu &rarr;</a>
    </div>
</div>
@endsection
