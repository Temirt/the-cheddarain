@extends('layouts.admin')

@section('title', 'Menu & Products CRUD — The Cheddarain Admin')

@section('admin_content')
<div>
    <!-- Page Header -->
    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.8rem; flex-wrap: wrap; gap: 1rem;">
        <div>
            <div style="display: flex; align-items: center; gap: 0.6rem; margin-bottom: 0.4rem;">
                <span class="badge badge-cheddar" style="font-size: 0.75rem;">Menu Manager</span>
                <span style="font-size: 0.85rem; color: var(--text-dim);">Total {{ $counts['total'] }} items</span>
            </div>
            <h1 style="font-size: clamp(1.6rem, 3vw, 2.2rem); margin-bottom: 0.3rem;">Menu & Products</h1>
            <p style="color: var(--text-cream-muted); font-size: 0.95rem;">Create, update, toggle availability, or delete food and beverage items.</p>
        </div>

        <a href="{{ route('admin.products.create') }}" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.4rem; box-shadow: var(--shadow-glow);">
            <span style="font-size: 1.2rem; font-weight: 800; line-height: 1;">+</span>
            <span style="font-weight: 700;">Add New Product</span>
        </a>
    </div>

    <!-- Quick Stats Bar -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 0.8rem; margin-bottom: 1.8rem;">
        <a href="{{ route('admin.products.index') }}" style="text-decoration: none;">
            <div style="background: var(--bg-card); border: 1px solid {{ !request('status') && !request('category_id') ? 'var(--cheddar-primary)' : 'var(--border-subtle)' }}; border-radius: var(--radius-md); padding: 0.85rem 1rem; transition: all 0.2s ease;">
                <div style="font-size: 0.75rem; text-transform: uppercase; color: var(--text-cream-muted); letter-spacing: 0.05em;">All Items</div>
                <div style="font-size: 1.4rem; font-weight: 800; color: var(--gold-bright);">{{ $counts['total'] }}</div>
            </div>
        </a>
        <a href="{{ route('admin.products.index', array_merge(request()->query(), ['status' => 'available'])) }}" style="text-decoration: none;">
            <div style="background: var(--bg-card); border: 1px solid {{ request('status') === 'available' ? 'var(--success)' : 'var(--border-subtle)' }}; border-radius: var(--radius-md); padding: 0.85rem 1rem; transition: all 0.2s ease;">
                <div style="font-size: 0.75rem; text-transform: uppercase; color: var(--text-cream-muted); letter-spacing: 0.05em;">In Stock</div>
                <div style="font-size: 1.4rem; font-weight: 800; color: var(--success);">{{ $counts['available'] }}</div>
            </div>
        </a>
        <a href="{{ route('admin.products.index', array_merge(request()->query(), ['status' => 'out_of_stock'])) }}" style="text-decoration: none;">
            <div style="background: var(--bg-card); border: 1px solid {{ request('status') === 'out_of_stock' ? 'var(--danger)' : 'var(--border-subtle)' }}; border-radius: var(--radius-md); padding: 0.85rem 1rem; transition: all 0.2s ease;">
                <div style="font-size: 0.75rem; text-transform: uppercase; color: var(--text-cream-muted); letter-spacing: 0.05em;">Out of Stock</div>
                <div style="font-size: 1.4rem; font-weight: 800; color: var(--danger);">{{ $counts['out_of_stock'] }}</div>
            </div>
        </a>
    </div>

    <!-- Filter & Search Toolbar (Responsive) -->
    <div style="background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-lg); padding: 1.2rem; margin-bottom: 2rem;">
        <form action="{{ route('admin.products.index') }}" method="GET" style="display: flex; gap: 0.8rem; flex-wrap: wrap; align-items: center;">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif

            <!-- Search Field -->
            <div style="flex: 2; min-width: 220px;">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="🔍 Search menu by name, badge, or description..." class="form-input" style="padding: 0.65rem 0.9rem; font-size: 0.9rem;">
            </div>

            <!-- Category Selector -->
            <div style="flex: 1; min-width: 170px;">
                <select name="category_id" class="form-select" style="padding: 0.65rem 0.9rem; font-size: 0.9rem;" onchange="this.form.submit()">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->icon ?? '🍔' }} {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-secondary btn-sm" style="padding: 0.65rem 1.1rem;">
                Filter
            </button>

            @if(request('search') || request('category_id') || request('status'))
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary btn-sm" style="padding: 0.65rem 0.9rem; color: var(--text-dim);" title="Reset filters">
                    ✕ Clear
                </a>
            @endif
        </form>

        <!-- Category Quick Pills -->
        <div style="display: flex; gap: 0.4rem; overflow-x: auto; padding-top: 0.8rem; margin-top: 0.8rem; border-top: 1px solid var(--border-subtle); scrollbar-width: none;">
            <a href="{{ route('admin.products.index', array_filter(['status' => request('status'), 'search' => request('search')])) }}" 
               class="badge {{ !request('category_id') ? 'badge-cheddar' : 'badge-gold' }}" 
               style="text-decoration: none; padding: 0.35rem 0.75rem; white-space: nowrap; font-size: 0.78rem;">
               All
            </a>
            @foreach($categories as $cat)
                <a href="{{ route('admin.products.index', array_filter(['category_id' => $cat->id, 'status' => request('status'), 'search' => request('search')])) }}" 
                   class="badge {{ request('category_id') == $cat->id ? 'badge-cheddar' : 'badge-gold' }}" 
                   style="text-decoration: none; padding: 0.35rem 0.75rem; white-space: nowrap; font-size: 0.78rem;">
                   {{ $cat->icon ?? '🍔' }} {{ $cat->name }}
                </a>
            @endforeach
        </div>
    </div>

    @if($products->isEmpty())
        <div style="background: var(--bg-card); border: 1px dashed var(--border-subtle); border-radius: var(--radius-lg); padding: 3rem 1.5rem; text-align: center;">
            <div style="font-size: 3rem; margin-bottom: 0.8rem;">🍔</div>
            <h3 style="font-size: 1.3rem; margin-bottom: 0.5rem; color: var(--gold-bright);">No Menu Items Found</h3>
            <p style="color: var(--text-cream-muted); margin-bottom: 1.5rem; max-width: 450px; margin-left: auto; margin-right: auto;">
                No products match your current search or category filter. Try clearing filters or create a new burger creation!
            </p>
            <div style="display: flex; gap: 0.8rem; justify-content: center; flex-wrap: wrap;">
                @if(request('search') || request('category_id') || request('status'))
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Clear All Filters</a>
                @endif
                <a href="{{ route('admin.products.create') }}" class="btn btn-primary">+ Add New Product</a>
            </div>
        </div>
    @else

        <!-- DESKTOP VERSION: High-density Data Table (Visible on screens >= 901px) -->
        <div class="admin-product-desktop-table data-table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 70px;">Image</th>
                        <th>Product Name & Badge</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Prep Time</th>
                        <th>Calories</th>
                        <th>Stock Status</th>
                        <th style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                        <tr>
                            <td>
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" 
                                     style="width: 54px; height: 54px; border-radius: var(--radius-sm); object-fit: cover; background: var(--bg-surface); border: 1px solid var(--border-subtle);"
                                     onerror="this.src='https://images.unsplash.com/photo-1568901346375-23c9450c58cd?auto=format&fit=crop&w=150&q=80'">
                            </td>
                            <td>
                                <div style="display: flex; flex-direction: column; gap: 0.2rem;">
                                    <div style="font-weight: 700; color: var(--text-cream); font-size: 0.98rem;">{{ $product->name }}</div>
                                    <div style="display: flex; gap: 0.35rem; align-items: center; flex-wrap: wrap;">
                                        @if($product->badge)
                                            <span class="badge badge-cheddar" style="font-size: 0.68rem; padding: 0.15rem 0.5rem;">{{ $product->badge }}</span>
                                        @endif
                                        @if($product->is_featured)
                                            <span class="badge badge-gold" style="font-size: 0.68rem; padding: 0.15rem 0.5rem;">⭐ Featured</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-gold" style="font-size: 0.82rem;">
                                    {{ $product->category->name ?? 'General' }}
                                </span>
                            </td>
                            <td style="font-weight: 800; color: var(--gold-bright); font-size: 1.1rem; white-space: nowrap;">
                                ${{ number_format($product->price, 2) }}
                            </td>
                            <td style="font-size: 0.88rem; color: var(--text-cream-soft); white-space: nowrap;">
                                ⏱️ {{ $product->prep_time_minutes }} mins
                            </td>
                            <td style="font-size: 0.85rem; color: var(--text-cream-muted);">
                                {{ $product->calories ? $product->calories . ' kcal' : '—' }}
                            </td>
                            <td>
                                <form action="{{ route('admin.products.toggle', $product->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" 
                                            class="badge {{ $product->is_available ? 'badge-success' : 'badge-danger' }}" 
                                            style="cursor: pointer; border: none; padding: 0.4rem 0.8rem; font-size: 0.82rem; transition: transform 0.1s ease;"
                                            title="Click to toggle availability">
                                        {{ $product->is_available ? '✓ Available' : '✕ Out of Stock' }}
                                    </button>
                                </form>
                            </td>
                            <td style="text-align: right;">
                                <div style="display: inline-flex; gap: 0.45rem; justify-content: flex-end;">
                                    <a href="{{ route('product.detail', $product->slug) }}" target="_blank" class="btn btn-secondary btn-sm" style="padding: 0.35rem 0.6rem; font-size: 0.8rem;" title="View Live Product Page">
                                        👁️ View
                                    </a>
                                    <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-secondary btn-sm" style="padding: 0.35rem 0.75rem; font-size: 0.82rem; color: var(--gold-bright);" title="Edit Product">
                                        ✏️ Edit
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Permanently remove {{ addslashes($product->name) }} from the menu?');" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-secondary btn-sm" style="padding: 0.35rem 0.65rem; color: var(--danger); font-size: 0.82rem;" title="Delete Product">
                                            🗑️
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- MOBILE VERSION: Touch-friendly Product Cards (Visible on screens <= 900px) -->
        <div class="admin-product-mobile-cards">
            @foreach($products as $product)
                <div style="background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-lg); padding: 1.1rem; box-shadow: var(--shadow-sm);">
                    <!-- Mobile Card Header: Image + Name + Category + Price -->
                    <div style="display: flex; gap: 0.9rem; align-items: flex-start; margin-bottom: 0.8rem;">
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" 
                             style="width: 68px; height: 68px; border-radius: var(--radius-md); object-fit: cover; background: var(--bg-surface); border: 1px solid var(--border-subtle); flex-shrink: 0;"
                             onerror="this.src='https://images.unsplash.com/photo-1568901346375-23c9450c58cd?auto=format&fit=crop&w=150&q=80'">
                        
                        <div style="flex: 1; min-width: 0;">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 0.5rem; margin-bottom: 0.2rem;">
                                <h3 style="font-size: 1.05rem; font-weight: 700; color: var(--text-cream); margin: 0; line-height: 1.3;">
                                    {{ $product->name }}
                                </h3>
                                <div style="font-weight: 800; color: var(--gold-bright); font-size: 1.15rem; white-space: nowrap;">
                                    ${{ number_format($product->price, 2) }}
                                </div>
                            </div>

                            <div style="display: flex; gap: 0.4rem; align-items: center; flex-wrap: wrap; margin-top: 0.3rem;">
                                <span class="badge badge-gold" style="font-size: 0.72rem; padding: 0.15rem 0.5rem;">
                                    {{ $product->category->name ?? 'General' }}
                                </span>
                                @if($product->badge)
                                    <span class="badge badge-cheddar" style="font-size: 0.7rem; padding: 0.15rem 0.5rem;">{{ $product->badge }}</span>
                                @endif
                                @if($product->is_featured)
                                    <span class="badge badge-gold" style="font-size: 0.7rem; padding: 0.15rem 0.5rem;">⭐ Featured</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Mobile Card Details: Prep Time & Calories -->
                    <div style="display: flex; justify-content: space-between; font-size: 0.82rem; color: var(--text-cream-muted); padding: 0.5rem 0; border-top: 1px solid var(--border-subtle); border-bottom: 1px solid var(--border-subtle); margin-bottom: 0.9rem;">
                        <span>⏱️ Prep: <strong style="color: var(--text-cream);">{{ $product->prep_time_minutes }} mins</strong></span>
                        <span>🔥 Energy: <strong style="color: var(--text-cream);">{{ $product->calories ? $product->calories . ' kcal' : '—' }}</strong></span>
                    </div>

                    <!-- Mobile Card Action Controls: Availability Toggle + Edit + Delete -->
                    <div style="display: flex; gap: 0.6rem; align-items: center;">
                        <!-- Direct Availability Toggle -->
                        <form action="{{ route('admin.products.toggle', $product->id) }}" method="POST" style="flex: 1;">
                            @csrf
                            <button type="submit" 
                                    class="badge {{ $product->is_available ? 'badge-success' : 'badge-danger' }}" 
                                    style="width: 100%; justify-content: center; padding: 0.65rem 0.5rem; font-size: 0.84rem; cursor: pointer; border: none; font-weight: 700; border-radius: var(--radius-md);">
                                {{ $product->is_available ? '✓ In Stock' : '✕ Out of Stock' }}
                            </button>
                        </form>

                        <!-- Edit Button -->
                        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-secondary btn-sm" style="flex: 1; text-align: center; justify-content: center; padding: 0.65rem 0.5rem; font-size: 0.84rem; color: var(--gold-bright);">
                            ✏️ Edit
                        </a>

                        <!-- Delete Button -->
                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Delete {{ addslashes($product->name) }}?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-secondary btn-sm" style="padding: 0.65rem 0.75rem; color: var(--danger); font-size: 0.88rem;" title="Delete">
                                🗑️
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

    @endif

    <!-- Pagination -->
    <div style="margin-top: 2rem;">
        {{ $products->links() }}
    </div>
</div>
@endsection
