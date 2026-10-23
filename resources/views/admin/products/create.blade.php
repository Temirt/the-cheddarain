@extends('layouts.admin')

@section('title', 'Add New Menu Item — The Cheddarain Admin')

@section('admin_content')
<div style="max-width: 860px; margin: 0 auto;">
    <!-- Breadcrumb & Header -->
    <div style="margin-bottom: 1.8rem;">
        <a href="{{ route('admin.products.index') }}" style="font-size: 0.88rem; color: var(--gold-bright); text-decoration: none; display: inline-flex; align-items: center; gap: 0.4rem; margin-bottom: 0.6rem;">
            &larr; Back to Products List
        </a>
        <h1 style="font-size: clamp(1.6rem, 3vw, 2.2rem); margin-bottom: 0.3rem;">Add Menu Item</h1>
        <p style="color: var(--text-cream-muted); font-size: 0.95rem;">Introduce a new artisan burger, loaded side, or sauce creation to your live menu.</p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger" style="margin-bottom: 1.5rem;">
            <span>✕</span>
            <span>{{ $errors->first() }}</span>
        </div>
    @endif

    <div style="background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-xl); padding: clamp(1.2rem, 3vw, 2.2rem); box-shadow: var(--shadow-md);">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Responsive Form Grid -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.3rem;">
                <!-- Product Name -->
                <div class="form-group" style="grid-column: 1 / -1;">
                    <label class="form-label" for="name">Product Name *</label>
                    <input type="text" name="name" id="name" class="form-input" required value="{{ old('name') }}" placeholder="e.g. The Truffle Gold Melt" autofocus>
                </div>

                <!-- Category -->
                <div class="form-group">
                    <label class="form-label" for="category_id">Menu Category *</label>
                    <select name="category_id" id="category_id" class="form-select" required>
                        <option value="" disabled {{ old('category_id') ? '' : 'selected' }}>Select Category...</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->icon ?? '🍔' }} {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Price -->
                <div class="form-group">
                    <label class="form-label" for="price">Price ($) *</label>
                    <input type="number" step="0.01" min="0" name="price" id="price" class="form-input" required value="{{ old('price', '14.99') }}" placeholder="14.99">
                </div>

                <!-- Description -->
                <div class="form-group" style="grid-column: 1 / -1;">
                    <label class="form-label" for="description">Product Description *</label>
                    <textarea name="description" id="description" class="form-textarea" required rows="3" placeholder="Describe the ingredients, artisan brioche bun, Wisconsin cheddar blend, and savory taste profile...">{{ old('description') }}</textarea>
                </div>

                <!-- Image URL & Live Preview -->
                <div class="form-group" style="grid-column: 1 / -1;">
                    <label class="form-label" for="image_url">Food Photography Image URL</label>
                    <div style="display: flex; gap: 1rem; align-items: flex-start; flex-wrap: wrap;">
                        <div style="flex: 1; min-width: 240px;">
                            <input type="url" name="image_url" id="image_url" class="form-input" 
                                   value="{{ old('image_url', 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?auto=format&fit=crop&w=800&q=80') }}" 
                                   placeholder="https://..." oninput="updateLivePreview(this.value)">
                            <span class="form-hint">Direct link to high-resolution photography, or choose a preset below</span>

                            <!-- Quick Preset Photo Chips -->
                            <div style="margin-top: 0.6rem;">
                                <div style="font-size: 0.76rem; color: var(--text-dim); margin-bottom: 0.35rem;">Quick Sample Photos:</div>
                                <div style="display: flex; gap: 0.4rem; flex-wrap: wrap;">
                                    <button type="button" class="btn btn-secondary btn-sm" style="font-size: 0.74rem; padding: 0.25rem 0.55rem;" onclick="setSampleImage('https://images.unsplash.com/photo-1568901346375-23c9450c58cd?auto=format&fit=crop&w=800&q=80')">🍔 Smashed Burger</button>
                                    <button type="button" class="btn btn-secondary btn-sm" style="font-size: 0.74rem; padding: 0.25rem 0.55rem;" onclick="setSampleImage('https://images.unsplash.com/photo-1586190848861-99aa4a171e90?auto=format&fit=crop&w=800&q=80')">🧀 Double Melt</button>
                                    <button type="button" class="btn btn-secondary btn-sm" style="font-size: 0.74rem; padding: 0.25rem 0.55rem;" onclick="setSampleImage('https://images.unsplash.com/photo-1576107232684-1279f3908594?auto=format&fit=crop&w=800&q=80')">🍟 Loaded Fries</button>
                                    <button type="button" class="btn btn-secondary btn-sm" style="font-size: 0.74rem; padding: 0.25rem 0.55rem;" onclick="setSampleImage('https://images.unsplash.com/photo-1550547660-d9450f859349?auto=format&fit=crop&w=800&q=80')">🍗 Chicken Sandwich</button>
                                    <button type="button" class="btn btn-secondary btn-sm" style="font-size: 0.74rem; padding: 0.25rem 0.55rem;" onclick="setSampleImage('https://images.unsplash.com/photo-1572490122747-3968b75cc699?auto=format&fit=crop&w=800&q=80')">🥤 Milkshake</button>
                                </div>
                            </div>

                            <!-- Mobile Camera / Upload -->
                            <div style="margin-top: 0.9rem; padding-top: 0.8rem; border-top: 1px dashed var(--border-subtle);">
                                <label style="font-size: 0.82rem; color: var(--text-cream-muted); display: block; margin-bottom: 0.35rem;">Or Upload Image from Device (Camera / Gallery):</label>
                                <input type="file" name="image_file" id="image_file" accept="image/*" class="form-input" style="padding: 0.5rem;" onchange="previewUploadedFile(this)">
                            </div>
                        </div>

                        <!-- Live Preview Thumbnail -->
                        <div style="text-align: center; width: 120px;">
                            <div style="width: 110px; height: 110px; border-radius: var(--radius-md); border: 2px solid var(--border-accent); overflow: hidden; background: var(--bg-surface); margin: 0 auto 0.4rem; box-shadow: var(--shadow-sm);">
                                <img id="liveImagePreview" src="{{ old('image_url', 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?auto=format&fit=crop&w=800&q=80') }}" 
                                     alt="Preview" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <span style="font-size: 0.75rem; color: var(--gold-bright);">Live Preview</span>
                        </div>
                    </div>
                </div>

                <!-- Promo Badge -->
                <div class="form-group">
                    <label class="form-label" for="badge">Promo Badge (Optional)</label>
                    <input type="text" name="badge" id="badge" class="form-input" value="{{ old('badge') }}" placeholder="e.g. Signature, Spicy, Best Seller">
                    <div style="display: flex; gap: 0.35rem; flex-wrap: wrap; margin-top: 0.4rem;">
                        <button type="button" class="badge badge-cheddar" style="cursor: pointer; border: none; font-size: 0.68rem;" onclick="setBadge('Signature')">Signature</button>
                        <button type="button" class="badge badge-gold" style="cursor: pointer; border: none; font-size: 0.68rem;" onclick="setBadge('Best Seller')">Best Seller</button>
                        <button type="button" class="badge badge-danger" style="cursor: pointer; border: none; font-size: 0.68rem;" onclick="setBadge('Spicy 🔥')">Spicy 🔥</button>
                        <button type="button" class="badge badge-cheddar" style="cursor: pointer; border: none; font-size: 0.68rem;" onclick="setBadge('Chef Special')">Chef Special</button>
                    </div>
                </div>

                <!-- Kitchen Prep Time -->
                <div class="form-group">
                    <label class="form-label" for="prep_time_minutes">Prep Time (Minutes) *</label>
                    <input type="number" min="1" max="120" name="prep_time_minutes" id="prep_time_minutes" class="form-input" required value="{{ old('prep_time_minutes', '15') }}">
                </div>

                <!-- Calories -->
                <div class="form-group">
                    <label class="form-label" for="calories">Calories (kcal, Optional)</label>
                    <input type="number" min="0" name="calories" id="calories" class="form-input" value="{{ old('calories', '850') }}" placeholder="850">
                </div>

                <!-- Sort Order -->
                <div class="form-group">
                    <label class="form-label" for="sort_order">Display Priority (Sort Order)</label>
                    <input type="number" name="sort_order" id="sort_order" class="form-input" value="{{ old('sort_order', '0') }}" placeholder="0">
                </div>

                <!-- Availability & Featured Toggles -->
                <div class="form-group" style="grid-column: 1 / -1; background: var(--bg-surface); padding: 1.1rem; border-radius: var(--radius-md); border: 1px solid var(--border-subtle);">
                    <div style="font-weight: 700; color: var(--gold-bright); font-size: 0.92rem; margin-bottom: 0.6rem;">Status & Visibility Options:</div>
                    
                    <div style="display: flex; flex-direction: column; gap: 0.8rem;">
                        <label style="display: flex; align-items: center; gap: 0.75rem; cursor: pointer; color: var(--text-cream); font-size: 0.92rem;">
                            <input type="checkbox" name="is_available" value="1" checked style="width: 18px; height: 18px; accent-color: var(--cheddar-bright);">
                            <span><strong>Immediately Available for Order</strong> (Visible & orderable on customer storefront)</span>
                        </label>

                        <label style="display: flex; align-items: center; gap: 0.75rem; cursor: pointer; color: var(--text-cream); font-size: 0.92rem;">
                            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} style="width: 18px; height: 18px; accent-color: var(--cheddar-bright);">
                            <span><strong>Featured on Homepage</strong> (Highlighted in Chef's Recommendations showcase)</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Action Buttons (Responsive: side-by-side on desktop, stacked on mobile) -->
            <div style="display: flex; gap: 0.9rem; margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--border-subtle); flex-wrap: wrap;">
                <button type="submit" class="btn btn-primary btn-lg" style="flex: 2; min-width: 200px; justify-content: center; font-weight: 700;">
                    <span>Create Menu Item &rarr;</span>
                </button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary btn-lg" style="flex: 1; min-width: 120px; text-align: center; justify-content: center;">
                    Cancel
                </a>
            </div>
        </form>
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
