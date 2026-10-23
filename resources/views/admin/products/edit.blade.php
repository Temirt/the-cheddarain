@extends('layouts.admin')

@section('title', 'Edit ' . $product->name . ' — The Cheddarain Admin')

@section('admin_content')
<div style="max-width: 860px; margin: 0 auto;">
    <!-- Breadcrumb & Header -->
    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.8rem; flex-wrap: wrap; gap: 1rem;">
        <div>
            <a href="{{ route('admin.products.index') }}" style="font-size: 0.88rem; color: var(--gold-bright); text-decoration: none; display: inline-flex; align-items: center; gap: 0.4rem; margin-bottom: 0.6rem;">
                &larr; Back to Products List
            </a>
            <h1 style="font-size: clamp(1.6rem, 3vw, 2.2rem); margin-bottom: 0.3rem;">Edit: {{ $product->name }}</h1>
            <p style="color: var(--text-cream-muted); font-size: 0.95rem;">Update pricing, photography, ingredients, or stock status.</p>
        </div>

        <div style="display: flex; gap: 0.6rem; align-items: center;">
            <a href="{{ route('product.detail', $product->slug) }}" target="_blank" class="btn btn-secondary btn-sm" style="display: inline-flex; align-items: center; gap: 0.4rem;">
                <span>👁️ View on Store</span>
            </a>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger" style="margin-bottom: 1.5rem;">
            <span>✕</span>
            <span>{{ $errors->first() }}</span>
        </div>
    @endif

    <div style="background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-xl); padding: clamp(1.2rem, 3vw, 2.2rem); box-shadow: var(--shadow-md);">
        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Responsive Form Grid -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.3rem;">
                <!-- Product Name -->
                <div class="form-group" style="grid-column: 1 / -1;">
                    <label class="form-label" for="name">Product Name *</label>
                    <input type="text" name="name" id="name" class="form-input" required value="{{ old('name', $product->name) }}" placeholder="e.g. The Truffle Gold Melt">
                </div>

                <!-- Category -->
                <div class="form-group">
                    <label class="form-label" for="category_id">Menu Category *</label>
                    <select name="category_id" id="category_id" class="form-select" required>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->icon ?? '🍔' }} {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Price -->
                <div class="form-group">
                    <label class="form-label" for="price">Price ($) *</label>
                    <input type="number" step="0.01" min="0" name="price" id="price" class="form-input" required value="{{ old('price', $product->price) }}">
                </div>

                <!-- Description -->
                <div class="form-group" style="grid-column: 1 / -1;">
                    <label class="form-label" for="description">Product Description *</label>
                    <textarea name="description" id="description" class="form-textarea" required rows="3">{{ old('description', $product->description) }}</textarea>
                </div>

                <!-- Image URL & Live Preview -->
                <div class="form-group" style="grid-column: 1 / -1;">
                    <label class="form-label" for="image_url">Food Photography Image URL</label>
                    <div style="display: flex; gap: 1rem; align-items: flex-start; flex-wrap: wrap;">
                        <div style="flex: 1; min-width: 240px;">
                            <input type="url" name="image_url" id="image_url" class="form-input" 
                                   value="{{ old('image_url', $product->image_url) }}" 
                                   placeholder="https://..." oninput="updateLivePreview(this.value)">
                            <span class="form-hint">Direct link to high-resolution photography, or choose a preset below</span>

                            <!-- Quick Preset Photo Chips -->
                            <div style="margin-top: 0.6rem;">
                                <div style="font-size: 0.76rem; color: var(--text-dim); margin-bottom: 0.35rem;">Sample Preset Photos:</div>
                                <div style="display: flex; gap: 0.4rem; flex-wrap: wrap;">
                                    <button type="button" class="btn btn-secondary btn-sm" style="font-size: 0.74rem; padding: 0.25rem 0.55rem;" onclick="setSampleImage('https://images.unsplash.com/photo-1568901346375-23c9450c58cd?auto=format&fit=crop&w=800&q=80')">🍔 Smashed</button>
                                    <button type="button" class="btn btn-secondary btn-sm" style="font-size: 0.74rem; padding: 0.25rem 0.55rem;" onclick="setSampleImage('https://images.unsplash.com/photo-1586190848861-99aa4a171e90?auto=format&fit=crop&w=800&q=80')">🧀 Double Melt</button>
                                    <button type="button" class="btn btn-secondary btn-sm" style="font-size: 0.74rem; padding: 0.25rem 0.55rem;" onclick="setSampleImage('https://images.unsplash.com/photo-1576107232684-1279f3908594?auto=format&fit=crop&w=800&q=80')">🍟 Fries</button>
                                    <button type="button" class="btn btn-secondary btn-sm" style="font-size: 0.74rem; padding: 0.25rem 0.55rem;" onclick="setSampleImage('https://images.unsplash.com/photo-1550547660-d9450f859349?auto=format&fit=crop&w=800&q=80')">🍗 Chicken</button>
                                    <button type="button" class="btn btn-secondary btn-sm" style="font-size: 0.74rem; padding: 0.25rem 0.55rem;" onclick="setSampleImage('https://images.unsplash.com/photo-1572490122747-3968b75cc699?auto=format&fit=crop&w=800&q=80')">🥤 Milkshake</button>
                                </div>
                            </div>

                            <!-- Mobile Camera / Upload -->
                            <div style="margin-top: 0.9rem; padding-top: 0.8rem; border-top: 1px dashed var(--border-subtle);">
                                <label style="font-size: 0.82rem; color: var(--text-cream-muted); display: block; margin-bottom: 0.35rem;">Or Upload New Image from Device:</label>
                                <input type="file" name="image_file" id="image_file" accept="image/*" class="form-input" style="padding: 0.5rem;" onchange="previewUploadedFile(this)">
                            </div>
                        </div>

                        <!-- Live Preview Thumbnail -->
                        <div style="text-align: center; width: 120px;">
                            <div style="width: 110px; height: 110px; border-radius: var(--radius-md); border: 2px solid var(--border-accent); overflow: hidden; background: var(--bg-surface); margin: 0 auto 0.4rem; box-shadow: var(--shadow-sm);">
                                <img id="liveImagePreview" src="{{ old('image_url', $product->image_url) }}" 
                                     alt="Preview" style="width: 100%; height: 100%; object-fit: cover;"
                                     onerror="this.src='https://images.unsplash.com/photo-1568901346375-23c9450c58cd?auto=format&fit=crop&w=800&q=80'">
                            </div>
                            <span style="font-size: 0.75rem; color: var(--gold-bright);">Photo Preview</span>
                        </div>
                    </div>
                </div>

                <!-- Promo Badge -->
                <div class="form-group">
                    <label class="form-label" for="badge">Promo Badge (Optional)</label>
                    <input type="text" name="badge" id="badge" class="form-input" value="{{ old('badge', $product->badge) }}" placeholder="e.g. Signature, Spicy, Best Seller">
                    <div style="display: flex; gap: 0.35rem; flex-wrap: wrap; margin-top: 0.4rem;">
                        <button type="button" class="badge badge-cheddar" style="cursor: pointer; border: none; font-size: 0.68rem;" onclick="setBadge('Signature')">Signature</button>
                        <button type="button" class="badge badge-gold" style="cursor: pointer; border: none; font-size: 0.68rem;" onclick="setBadge('Best Seller')">Best Seller</button>
                        <button type="button" class="badge badge-danger" style="cursor: pointer; border: none; font-size: 0.68rem;" onclick="setBadge('Spicy 🔥')">Spicy 🔥</button>
                        <button type="button" class="badge badge-cheddar" style="cursor: pointer; border: none; font-size: 0.68rem;" onclick="setBadge('Chef Special')">Chef Special</button>
                    </div>
                </div>

                <!-- Kitchen Prep Time -->
                <div class="form-group">
                    <label class="form-label" for="prep_time_minutes">Kitchen Prep Time (Minutes) *</label>
                    <input type="number" min="1" max="120" name="prep_time_minutes" id="prep_time_minutes" class="form-input" required value="{{ old('prep_time_minutes', $product->prep_time_minutes) }}">
                </div>

                <!-- Calories -->
                <div class="form-group">
                    <label class="form-label" for="calories">Calories (kcal, Optional)</label>
                    <input type="number" min="0" name="calories" id="calories" class="form-input" value="{{ old('calories', $product->calories) }}">
                </div>

                <!-- Sort Order -->
                <div class="form-group">
                    <label class="form-label" for="sort_order">Display Priority (Sort Order)</label>
                    <input type="number" name="sort_order" id="sort_order" class="form-input" value="{{ old('sort_order', $product->sort_order) }}">
                </div>

                <!-- Availability & Featured Toggles -->
                <div class="form-group" style="grid-column: 1 / -1; background: var(--bg-surface); padding: 1.1rem; border-radius: var(--radius-md); border: 1px solid var(--border-subtle);">
                    <div style="font-weight: 700; color: var(--gold-bright); font-size: 0.92rem; margin-bottom: 0.6rem;">Status & Visibility Options:</div>
                    
                    <div style="display: flex; flex-direction: column; gap: 0.8rem;">
                        <label style="display: flex; align-items: center; gap: 0.75rem; cursor: pointer; color: var(--text-cream); font-size: 0.92rem;">
                            <input type="checkbox" name="is_available" value="1" {{ old('is_available', $product->is_available) ? 'checked' : '' }} style="width: 18px; height: 18px; accent-color: var(--cheddar-bright);">
                            <span><strong>Immediately Available for Order</strong> (Visible & orderable on customer storefront)</span>
                        </label>

                        <label style="display: flex; align-items: center; gap: 0.75rem; cursor: pointer; color: var(--text-cream); font-size: 0.92rem;">
                            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }} style="width: 18px; height: 18px; accent-color: var(--cheddar-bright);">
                            <span><strong>Featured on Homepage</strong> (Highlighted in Chef's Recommendations showcase)</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Action Buttons (Responsive) -->
            <div style="display: flex; gap: 0.9rem; margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--border-subtle); flex-wrap: wrap;">
                <button type="submit" class="btn btn-primary btn-lg" style="flex: 2; min-width: 200px; justify-content: center; font-weight: 700;">
                    <span>Save Changes &rarr;</span>
                </button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary btn-lg" style="flex: 1; min-width: 120px; text-align: center; justify-content: center;">
                    Cancel
                </a>
            </div>
        </form>

        <!-- Danger Zone: Delete Product -->
        <div style="margin-top: 2.5rem; padding-top: 1.8rem; border-top: 1px dashed var(--border-subtle); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
            <div>
                <div style="font-weight: 700; color: var(--danger); font-size: 0.95rem;">Delete This Menu Item</div>
                <div style="font-size: 0.82rem; color: var(--text-cream-muted);">Once deleted, this item will be removed permanently from the menu and cart.</div>
            </div>
            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete {{ addslashes($product->name) }}? This action cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-secondary btn-sm" style="color: var(--danger); border-color: rgba(239, 68, 68, 0.4); padding: 0.55rem 1.1rem;">
                    🗑️ Permanently Delete Product
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function updateLivePreview(url) {
    if (url && url.trim().length > 5) {
        document.getElementById('liveImagePreview').src = url;
    }
}

function setSampleImage(url) {
    document.getElementById('image_url').value = url;
    updateLivePreview(url);
}

function setBadge(text) {
    document.getElementById('badge').value = text;
}

function previewUploadedFile(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('liveImagePreview').src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
