@extends('layouts.app')

@section('title', 'The Cheddarain — Melted Cheddar Burgers, Loaded Fries & Artisanal Sauces')

@section('content')
    <!-- Hero Banner -->
    <section class="hero-section">
        <div class="container hero-grid">
            <div class="hero-content">
                <div style="display: flex; gap: 0.8rem; margin-bottom: 1.2rem;">
                    <span class="badge badge-cheddar">🔥 Fresh Smashed Angus</span>
                    <span class="badge badge-gold">🧀 Double Melted Cheddar</span>
                </div>
                <h1>Dripping in <span class="text-cheddar">Cheddar</span>, Crafted to <span class="text-gold">Perfection</span>.</h1>
                <p class="hero-subtitle">
                    Experience the ultimate fast-casual indulgence. Crispy-edged smashed prime beef, double-drenched in our signature Wisconsin melted cheddar cascade, sandwiched in golden toasted brioche.
                </p>

                <div class="hero-cta-group">
                    <a href="{{ route('menu') }}" class="btn btn-primary btn-lg">
                        <span>🍔</span>
                        <span>Order Now</span>
                    </a>
                    <a href="{{ route('order.track') }}" class="btn btn-secondary btn-lg">
                        <span>📍</span>
                        <span>Track My Order</span>
                    </a>
                </div>

                <ul class="hero-features-list">
                    <li class="hero-feature-item">
                        <span style="color: var(--cheddar-bright); font-size: 1.2rem;">✓</span>
                        <span>100% Angus Beef</span>
                    </li>
                    <li class="hero-feature-item">
                        <span style="color: var(--cheddar-bright); font-size: 1.2rem;">✓</span>
                        <span>12-15 Min Fast Prep</span>
                    </li>
                    <li class="hero-feature-item">
                        <span style="color: var(--cheddar-bright); font-size: 1.2rem;">✓</span>
                        <span>Real-Time Tracking</span>
                    </li>
                </ul>
            </div>

            <div class="hero-image-wrapper">
                <div class="hero-image-card">
                    <img src="https://images.unsplash.com/photo-1568901346375-23c9450c58cd?auto=format&fit=crop&w=900&q=80" alt="The Cheddar Lava Burger" loading="eager">
                    <div class="hero-floating-badge">
                        <div class="floating-badge-icon">⭐</div>
                        <div class="floating-badge-text">
                            <strong>4.9 / 5.0 Rating</strong>
                            <span>Loved by 12,500+ burger fanatics</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Popular Categories Strip -->
    <section style="background: var(--bg-surface); padding: 3rem 0; border-top: 1px solid var(--border-subtle); border-bottom: 1px solid var(--border-subtle);">
        <div class="container">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem;">
                @foreach($categories as $cat)
                    <a href="{{ route('menu', ['category' => $cat->slug]) }}" class="category-tab-btn" style="justify-content: center; padding: 1.2rem; border-radius: var(--radius-lg); background: var(--bg-card);">
                        <span style="font-size: 1.8rem;">{{ $cat->icon ?? '🍔' }}</span>
                        <div style="text-align: left;">
                            <div style="font-weight: 700; color: var(--text-cream);">{{ $cat->name }}</div>
                            <div style="font-size: 0.78rem; color: var(--text-cream-muted);">Explore selection &rarr;</div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Featured Products Showcase -->
    <section class="section-spacing">
        <div class="container">
            <div class="section-title">
                <span class="badge badge-cheddar">Chef's Curated Selection</span>
                <h2>Signature Burgers & Meals</h2>
                <p>Each masterpiece is hand-smashed on our seasoned chrome griddle and drenched in warm melted gold.</p>
            </div>

            <div class="product-grid">
                @foreach($featuredProducts as $product)
                    <div class="product-card" data-category="{{ $product->category->slug ?? '' }}">
                        <div class="product-image-container">
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" loading="lazy">
                            @if($product->badge)
                                <div class="product-badge-overlay">
                                    <span class="badge badge-cheddar">{{ $product->badge }}</span>
                                </div>
                            @endif
                            @if($product->calories)
                                <div class="product-calories-overlay">
                                    🔥 {{ $product->calories }} kcal
                                </div>
                            @endif
                        </div>

                        <div class="product-info">
                            <div class="product-category-name">{{ $product->category->name ?? 'Gourmet' }}</div>
                            <h3 class="product-title">{{ $product->name }}</h3>
                            <p class="product-desc">{{ $product->description }}</p>

                            <div class="product-footer">
                                <div class="product-price">${{ number_format($product->price, 2) }}</div>
                                <div>
                                    @if($product->options->count() > 0)
                                        <button type="button" class="btn btn-primary btn-sm open-customize-btn" data-product-id="{{ $product->id }}">
                                            <span>Customize & Add</span>
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-primary btn-sm quick-add-btn"
                                                data-product-id="{{ $product->id }}"
                                                data-product-name="{{ $product->name }}"
                                                data-product-price="{{ $product->price }}"
                                                data-product-image="{{ $product->image_url }}">
                                            <span>+ Quick Add</span>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div style="text-align: center; margin-top: 3.5rem;">
                <a href="{{ route('menu') }}" class="btn btn-secondary btn-lg">
                    <span>View Full Artisan Menu ({{ $stats['total_burgers'] }} items)</span>
                    <span>&rarr;</span>
                </a>
            </div>
        </div>
    </section>

    <!-- How Ordering Works (3-Step Fast Flow) -->
    <section class="section-spacing" style="background: var(--bg-surface); border-top: 1px solid var(--border-subtle); border-bottom: 1px solid var(--border-subtle);">
        <div class="container">
            <div class="section-title">
                <span class="badge badge-gold">Lightning Quick</span>
                <h2>How It Works</h2>
                <p>From your screen to piping hot melted cheddar in minutes.</p>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 2rem;">
                <div style="background: var(--bg-card); padding: 2.2rem; border-radius: var(--radius-lg); border: 1px solid var(--border-subtle); text-align: center;">
                    <div style="width: 60px; height: 60px; background: rgba(255, 153, 0, 0.15); color: var(--cheddar-bright); font-size: 1.8rem; border-radius: var(--radius-full); display: flex; align-items: center; justify-content: center; margin: 0 auto 1.4rem auto; font-weight: 800;">1</div>
                    <h3 style="font-size: 1.25rem; margin-bottom: 0.6rem;">Pick Your Feast</h3>
                    <p style="font-size: 0.92rem; color: var(--text-cream-muted);">Select your favorite smash burger, customize bun style, add cheese upgrades, and pair with loaded sides.</p>
                </div>

                <div style="background: var(--bg-card); padding: 2.2rem; border-radius: var(--radius-lg); border: 1px solid var(--border-subtle); text-align: center;">
                    <div style="width: 60px; height: 60px; background: rgba(252, 191, 73, 0.15); color: var(--gold-bright); font-size: 1.8rem; border-radius: var(--radius-full); display: flex; align-items: center; justify-content: center; margin: 0 auto 1.4rem auto; font-weight: 800;">2</div>
                    <h3 style="font-size: 1.25rem; margin-bottom: 0.6rem;">Fresh Kitchen Prep</h3>
                    <p style="font-size: 0.92rem; color: var(--text-cream-muted);">Our grillmasters smash patties to order, toast brioche, and pour fresh hot cheddar over your creation.</p>
                </div>

                <div style="background: var(--bg-card); padding: 2.2rem; border-radius: var(--radius-lg); border: 1px solid var(--border-subtle); text-align: center;">
                    <div style="width: 60px; height: 60px; background: rgba(16, 185, 129, 0.15); color: var(--success); font-size: 1.8rem; border-radius: var(--radius-full); display: flex; align-items: center; justify-content: center; margin: 0 auto 1.4rem auto; font-weight: 800;">3</div>
                    <h3 style="font-size: 1.25rem; margin-bottom: 0.6rem;">Track & Savor</h3>
                    <p style="font-size: 0.92rem; color: var(--text-cream-muted);">Watch our live McDonald's-style progress timeline with auto-polling updates, then enjoy hot and fresh.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Customer Reviews -->
    <section class="section-spacing">
        <div class="container">
            <div class="section-title">
                <span class="badge badge-cheddar">Customer Reviews</span>
                <h2>What Burger Lovers Say</h2>
                <p>Authentic feedback from real patrons who experienced the melted gold.</p>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
                <div style="background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-lg); padding: 2rem;">
                    <div style="color: var(--gold-bright); font-size: 1.2rem; margin-bottom: 0.8rem;">★★★★★</div>
                    <p style="font-size: 0.98rem; font-style: italic; margin-bottom: 1.5rem; line-height: 1.6;">
                        "The Cheddar Lava is genuinely the best burger I've had in years. The cheese is decadent, not greasy, and the smash crust on the patties is perfection."
                    </p>
                    <div style="display: flex; align-items: center; gap: 0.8rem;">
                        <div style="width: 44px; height: 44px; border-radius: var(--radius-full); background: var(--bg-elevated); display: flex; align-items: center; justify-content: center; font-weight: 700; color: var(--cheddar-bright);">EM</div>
                        <div>
                            <div style="font-weight: 700; color: var(--text-cream);">Ethan Miller</div>
                            <div style="font-size: 0.8rem; color: var(--text-dim);">Verified Customer • Order #CHED-8491</div>
                        </div>
                    </div>
                </div>

                <div style="background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-lg); padding: 2rem;">
                    <div style="color: var(--gold-bright); font-size: 1.2rem; margin-bottom: 0.8rem;">★★★★★</div>
                    <p style="font-size: 0.98rem; font-style: italic; margin-bottom: 1.5rem; line-height: 1.6;">
                        "The live order tracker feels just like the McDonald's app but better! Watching the timeline change from kitchen prep to out for delivery made waiting fun."
                    </p>
                    <div style="display: flex; align-items: center; gap: 0.8rem;">
                        <div style="width: 44px; height: 44px; border-radius: var(--radius-full); background: var(--bg-elevated); display: flex; align-items: center; justify-content: center; font-weight: 700; color: var(--gold-bright);">SL</div>
                        <div>
                            <div style="font-weight: 700; color: var(--text-cream);">Sophia Laurent</div>
                            <div style="font-size: 0.8rem; color: var(--text-dim);">Verified Customer • Order #CHED-6204</div>
                        </div>
                    </div>
                </div>

                <div style="background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-lg); padding: 2rem;">
                    <div style="color: var(--gold-bright); font-size: 1.2rem; margin-bottom: 0.8rem;">★★★★★</div>
                    <p style="font-size: 0.98rem; font-style: italic; margin-bottom: 1.5rem; line-height: 1.6;">
                        "Loaded Cheddar Bacon Fries + Liquid Gold dip pot is an absolute cheat meal dream. Fast pickup at the counter and everything was piping hot."
                    </p>
                    <div style="display: flex; align-items: center; gap: 0.8rem;">
                        <div style="width: 44px; height: 44px; border-radius: var(--radius-full); background: var(--bg-elevated); display: flex; align-items: center; justify-content: center; font-weight: 700; color: var(--cheddar-bright);">JR</div>
                        <div>
                            <div style="font-weight: 700; color: var(--text-cream);">Marcus Jenkins</div>
                            <div style="font-size: 0.8rem; color: var(--text-dim);">Verified Customer • Order #CHED-7195</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Bottom CTA Banner -->
    <section style="padding: 5rem 0; background: radial-gradient(circle, rgba(255, 153, 0, 0.18) 0%, rgba(10, 11, 14, 0.95) 70%); text-align: center;">
        <div class="container container-narrow">
            <h2 style="margin-bottom: 1.2rem;">Ready to Taste the Cheddar Miracle?</h2>
            <p style="color: var(--text-cream-muted); font-size: 1.15rem; margin-bottom: 2.2rem;">
                Browse our menu, customize your feast, and follow your order in real time.
            </p>
            <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                <a href="{{ route('menu') }}" class="btn btn-primary btn-lg">Explore Menu</a>
                <a href="{{ route('order.track') }}" class="btn btn-secondary btn-lg">Track Existing Order</a>
            </div>
        </div>
    </section>
@endsection
