@extends('layouts.app')

@section('title', 'The Cheddarloin — The Art of the Burger')

@section('content')
    <!-- Cinematic Hero Section -->
    <section class="hero-section" id="hero">
        <div class="container hero-grid">
            <div class="hero-content">
                <div style="margin-bottom: 0.8rem;">
                    <span style="font-family: var(--font-body); font-size: 0.85rem; font-weight: 800; letter-spacing: 0.2em; color: var(--gold-primary); text-transform: uppercase;">
                        THE ART OF THE BURGER
                    </span>
                </div>
                <h1 style="font-family: var(--font-heading); font-size: clamp(2.5rem, 5.5vw, 4.2rem); font-weight: 800; line-height: 1.1; margin-bottom: 1.2rem; color: var(--text-cream);">
                    The <span style="color: var(--gold-primary);">Cheddarloin</span>
                </h1>
                <p class="hero-subtitle" style="font-size: 1.15rem; color: var(--text-secondary); max-width: 520px; line-height: 1.6; margin-bottom: 2rem;">
                    Burgers, elevated. Made your way and prepared for delivery.
                </p>

                <div class="hero-cta-group" style="display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 2.5rem;">
                    <a href="#menu" class="btn btn-primary btn-lg" style="padding: 0.95rem 2rem; font-size: 1rem; border-radius: var(--radius-full);">
                        <span>Explore the Menu</span>
                        <span>&darr;</span>
                    </a>
                    <a href="{{ route('orders') }}" class="btn btn-secondary btn-lg" style="padding: 0.95rem 1.8rem; font-size: 1rem; border-radius: var(--radius-full);">
                        <span>📦</span>
                        <span>My Orders</span>
                    </a>
                </div>

                <ul class="hero-features-list" style="display: flex; gap: 1.5rem; list-style: none; padding: 0; margin: 0; flex-wrap: wrap;">
                    <li style="display: flex; align-items: center; gap: 0.5rem; color: var(--text-cream-soft); font-size: 0.9rem;">
                        <span style="color: var(--gold-primary); font-weight: bold;">✓</span>
                        <span>Fresh Angus Beef</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: 0.5rem; color: var(--text-cream-soft); font-size: 0.9rem;">
                        <span style="color: var(--gold-primary); font-weight: bold;">✓</span>
                        <span>Double Melted Cheddar</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: 0.5rem; color: var(--text-cream-soft); font-size: 0.9rem;">
                        <span style="color: var(--gold-primary); font-weight: bold;">✓</span>
                        <span>Direct Delivery</span>
                    </li>
                </ul>
            </div>

            <div class="hero-image-wrapper">
                <div class="hero-image-card" style="border-radius: var(--radius-xl); overflow: hidden; box-shadow: 0 20px 50px rgba(0,0,0,0.8); border: 1px solid var(--border-color); position: relative;">
                    <img src="{{ asset('assets/images/hero-burger.jpg') }}" alt="The Cheddarloin Burger" loading="eager" style="width: 100%; height: 100%; object-fit: cover; display: block;">
                    <div class="hero-floating-badge" style="position: absolute; bottom: 20px; left: 20px; right: 20px; background: rgba(17, 16, 14, 0.88); backdrop-filter: blur(12px); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 0.9rem 1.2rem; display: flex; align-items: center; gap: 0.9rem;">
                        <div style="font-size: 1.6rem;">⭐</div>
                        <div>
                            <strong style="color: var(--gold-primary); display: block; font-size: 0.95rem;">4.9 / 5.0 Rating</strong>
                            <span style="color: var(--text-muted); font-size: 0.8rem;">Loved by burger enthusiasts across Addis Ababa</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Menu Section (Crafted To Order) -->
    <section class="section-spacing" id="menu" style="scroll-margin-top: 80px;">
        <div class="container">
            <div class="section-title" style="text-align: center; margin-bottom: 2.5rem;">
                <span style="font-family: var(--font-body); font-size: 0.85rem; font-weight: 800; letter-spacing: 0.18em; color: var(--gold-primary); text-transform: uppercase; display: block; margin-bottom: 0.5rem;">
                    CRAFTED TO ORDER
                </span>
                <h2 style="font-family: var(--font-heading); font-size: clamp(2rem, 4vw, 3rem); font-weight: 800; color: var(--text-cream); margin-bottom: 0.6rem;">
                    The Menu
                </h2>
                <p style="color: var(--text-secondary); font-size: 1.05rem; max-width: 580px; margin: 0 auto;">
                    Choose a favourite. Finish it your way.
                </p>
            </div>

            <!-- Instant Client-Side Category Filter Tabs -->
            <div class="menu-category-tabs" style="display: flex; gap: 0.75rem; justify-content: center; flex-wrap: wrap; margin-bottom: 3rem;">
                <button type="button" class="category-filter-btn active" data-category="all">
                    All Selection
                </button>
                @foreach($categories as $category)
                    <button type="button" class="category-filter-btn" data-category="{{ $category->slug }}">
                        {{ $category->icon ?? '' }} {{ $category->name }}
                    </button>
                @endforeach
            </div>

            <!-- Products Grid (Mobile single-column, Desktop 3-column) -->
            <div class="product-grid" id="product-grid">
                @foreach($products as $product)
                    <div class="product-card" data-category="{{ $product->category->slug ?? 'burgers' }}">
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
                                <div class="product-price">{{ $product->formatted_price }}</div>
                                <div style="display: flex; gap: 0.5rem; align-items: center;">
                                    @if($product->options->count() > 0)
                                        <button type="button" class="btn btn-secondary btn-sm open-customize-btn" data-product-id="{{ $product->id }}" title="Customize Bun & Toppings">
                                            <span>Customize</span>
                                        </button>
                                    @endif
                                    <button type="button" class="btn-add"
                                            data-product-id="{{ $product->id }}"
                                            data-product-name="{{ $product->name }}"
                                            data-product-price="{{ $product->price }}"
                                            data-product-image="{{ $product->image_url }}"
                                            aria-label="Add {{ $product->name }} to cart">
                                        <span>Add +</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- How Ordering Works (3-Step Luxury Flow) -->
    <section class="section-spacing" style="background: var(--bg-surface); border-top: 1px solid var(--border-color); border-bottom: 1px solid var(--border-color);">
        <div class="container">
            <div class="section-title" style="text-align: center; margin-bottom: 3rem;">
                <span style="font-size: 0.82rem; font-weight: 800; letter-spacing: 0.15em; color: var(--gold-primary); text-transform: uppercase; display: block; margin-bottom: 0.5rem;">
                    EFFORTLESS ORDERING
                </span>
                <h2 style="font-family: var(--font-heading); font-size: 2.2rem; color: var(--text-cream); margin-bottom: 0.6rem;">
                    How It Works
                </h2>
                <p style="color: var(--text-secondary); max-width: 500px; margin: 0 auto;">
                    From chrome griddle to your doorstep in minutes.
                </p>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 2rem;">
                <div style="background: var(--bg-card); padding: 2.2rem; border-radius: var(--radius-lg); border: 1px solid var(--border-color); text-align: center;">
                    <div style="width: 54px; height: 54px; background: rgba(243, 198, 109, 0.15); color: var(--gold-primary); font-size: 1.6rem; border-radius: var(--radius-full); display: flex; align-items: center; justify-content: center; margin: 0 auto 1.4rem auto; font-weight: 800; border: 1px solid var(--gold-border);">1</div>
                    <h3 style="font-size: 1.25rem; margin-bottom: 0.6rem; color: var(--text-cream);">Select Your Feast</h3>
                    <p style="font-size: 0.92rem; color: var(--text-secondary);">Browse our Angus patties, loaded cheddar sides, and iced beverages. Hit "Add +" to build your tray.</p>
                </div>

                <div style="background: var(--bg-card); padding: 2.2rem; border-radius: var(--radius-lg); border: 1px solid var(--border-color); text-align: center;">
                    <div style="width: 54px; height: 54px; background: rgba(243, 198, 109, 0.15); color: var(--gold-primary); font-size: 1.6rem; border-radius: var(--radius-full); display: flex; align-items: center; justify-content: center; margin: 0 auto 1.4rem auto; font-weight: 800; border: 1px solid var(--gold-border);">2</div>
                    <h3 style="font-size: 1.25rem; margin-bottom: 0.6rem; color: var(--text-cream);">Swift Checkout</h3>
                    <p style="font-size: 0.92rem; color: var(--text-secondary);">No lengthy account creation. Provide your name, phone, and delivery address. Pay in-person on arrival.</p>
                </div>

                <div style="background: var(--bg-card); padding: 2.2rem; border-radius: var(--radius-lg); border: 1px solid var(--border-color); text-align: center;">
                    <div style="width: 54px; height: 54px; background: rgba(243, 198, 109, 0.15); color: var(--gold-primary); font-size: 1.6rem; border-radius: var(--radius-full); display: flex; align-items: center; justify-content: center; margin: 0 auto 1.4rem auto; font-weight: 800; border: 1px solid var(--gold-border);">3</div>
                    <h3 style="font-size: 1.25rem; margin-bottom: 0.6rem; color: var(--text-cream);">Track & Savor</h3>
                    <p style="font-size: 0.92rem; color: var(--text-secondary);">Follow your 5-stage progress indicator in real-time under "My Orders", then enjoy fresh melted cheddar.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Bottom CTA Banner -->
    <section style="padding: 5rem 0; background: radial-gradient(circle, rgba(243, 198, 109, 0.12) 0%, rgba(8, 8, 7, 0.98) 70%); text-align: center;">
        <div class="container container-narrow">
            <h2 style="font-family: var(--font-heading); font-size: clamp(2rem, 3.5vw, 2.8rem); margin-bottom: 1.2rem; color: var(--text-cream);">
                Ready to Experience The Cheddarloin?
            </h2>
            <p style="color: var(--text-secondary); font-size: 1.15rem; margin-bottom: 2.2rem; max-width: 580px; margin-left: auto; margin-right: auto;">
                Handcrafted smashed Angus patties, golden fries, and our signature molten cheddar drizzle.
            </p>
            <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                <a href="#menu" class="btn btn-primary btn-lg" style="border-radius: var(--radius-full);">Explore Menu</a>
                <a href="{{ route('orders') }}" class="btn btn-secondary btn-lg" style="border-radius: var(--radius-full);">Track My Order</a>
            </div>
        </div>
    </section>
@endsection
