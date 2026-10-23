@extends('layouts.app')

@section('title', 'Artisan Menu — The Cheddarain')

@section('content')
<div class="container" style="padding-top: 3.5rem; padding-bottom: 5rem;">
    <div class="section-title">
        <span class="badge badge-cheddar">Fresh Daily Selection</span>
        <h2>Our Artisan Menu</h2>
        <p>Premium 100% Angus smash patties, loaded sides, hand-crafted dipping sauces, and feasts.</p>
    </div>

    <!-- Live Search Input -->
    <div class="menu-search-wrapper">
        <span class="menu-search-icon">🔍</span>
        <input type="text" id="menu-search-input" class="menu-search-input" placeholder="Search burgers, fries, or sauces..." value="{{ $search }}">
    </div>

    <!-- Category Filters -->
    <div class="category-tabs">
        <a href="{{ route('menu', ['category' => 'all']) }}" class="category-tab-btn {{ $selectedCategory === 'all' ? 'active' : '' }}">
            <span>🌟</span> All Items
        </a>
        @foreach($categories as $cat)
            <a href="{{ route('menu', ['category' => $cat->slug]) }}" class="category-tab-btn {{ $selectedCategory === $cat->slug ? 'active' : '' }}">
                <span>{{ $cat->icon ?? '🍔' }}</span> {{ $cat->name }}
            </a>
        @endforeach
    </div>

    <!-- Products Grid -->
    <div class="product-grid" id="product-grid">
        @forelse($products as $product)
            <div class="product-card" data-category="{{ $product->category->slug ?? '' }}">
                <a href="{{ route('product.detail', $product->slug) }}" class="product-image-container">
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
                </a>

                <div class="product-info">
                    <div class="product-category-name">{{ $product->category->name ?? 'Menu' }}</div>
                    <h3 class="product-title">
                        <a href="{{ route('product.detail', $product->slug) }}" style="color: inherit;">{{ $product->name }}</a>
                    </h3>
                    <p class="product-desc">{{ $product->description }}</p>

                    <div class="product-footer">
                        <div class="product-price">${{ number_format($product->price, 2) }}</div>
                        <div>
                            @if($product->options->count() > 0)
                                <button type="button" class="btn btn-primary btn-sm open-customize-btn" data-product-id="{{ $product->id }}">
                                    <span>Customize</span>
                                </button>
                            @else
                                <button type="button" class="btn btn-primary btn-sm quick-add-btn"
                                        data-product-id="{{ $product->id }}"
                                        data-product-name="{{ $product->name }}"
                                        data-product-price="{{ $product->price }}"
                                        data-product-image="{{ $product->image_url }}">
                                    <span>+ Add</span>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div style="grid-column: 1 / -1; text-align: center; padding: 4rem 1rem;">
                <div style="font-size: 3rem; margin-bottom: 1rem;">🍔</div>
                <h3>No items found</h3>
                <p style="color: var(--text-cream-muted); margin-bottom: 1.5rem;">Try adjusting your search terms or filter selection.</p>
                <a href="{{ route('menu') }}" class="btn btn-secondary">View All Menu Items</a>
            </div>
        @endforelse
    </div>
</div>
@endsection
