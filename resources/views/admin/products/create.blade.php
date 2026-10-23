@extends('layouts.admin')

@section('title', 'Add New Menu Item — The Cheddarain Admin')

@section('admin_content')
<div style="max-width: 800px;">
    <div style="margin-bottom: 2rem;">
        <h1 style="font-size: 2rem; margin-bottom: 0.3rem;">Add Menu Item</h1>
        <p style="color: var(--text-cream-muted); font-size: 0.95rem;">Introduce a new burger, loaded side, or sauce creation to the menu.</p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger" style="margin-bottom: 1.5rem;">
            <span>✕</span>
            <span>{{ $errors->first() }}</span>
        </div>
    @endif

    <div style="background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-lg); padding: 2.2rem;">
        <form action="{{ route('admin.products.store') }}" method="POST">
            @csrf

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.2rem;">
                <div class="form-group" style="grid-column: 1 / -1;">
                    <label class="form-label" for="name">Product Name *</label>
                    <input type="text" name="name" id="name" class="form-input" required value="{{ old('name') }}" placeholder="e.g. The Truffle Gold Melt">
                </div>

                <div class="form-group">
                    <label class="form-label" for="category_id">Category *</label>
                    <select name="category_id" id="category_id" class="form-select" required>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="price">Price ($) *</label>
                    <input type="number" step="0.01" name="price" id="price" class="form-input" required value="{{ old('price', '14.99') }}">
                </div>

                <div class="form-group" style="grid-column: 1 / -1;">
                    <label class="form-label" for="description">Product Description *</label>
                    <textarea name="description" id="description" class="form-textarea" required placeholder="Describe the ingredients, cheese cascade, and brioche bun...">{{ old('description') }}</textarea>
                </div>

                <div class="form-group" style="grid-column: 1 / -1;">
                    <label class="form-label" for="image_url">Image URL *</label>
                    <input type="url" name="image_url" id="image_url" class="form-input" value="{{ old('image_url', 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?auto=format&fit=crop&w=800&q=80') }}">
                    <span class="form-hint">Direct link to high-res food image</span>
                </div>

                <div class="form-group">
                    <label class="form-label" for="badge">Promo Badge (Optional)</label>
                    <input type="text" name="badge" id="badge" class="form-input" value="{{ old('badge') }}" placeholder="e.g. Signature, Spicy, Chef Special">
                </div>

                <div class="form-group">
                    <label class="form-label" for="calories">Calories (kcal)</label>
                    <input type="number" name="calories" id="calories" class="form-input" value="{{ old('calories', '850') }}">
                </div>

                <div class="form-group">
                    <label class="form-label" for="prep_time_minutes">Kitchen Prep Time (Minutes) *</label>
                    <input type="number" name="prep_time_minutes" id="prep_time_minutes" class="form-input" required value="{{ old('prep_time_minutes', '15') }}">
                </div>

                <div class="form-group" style="display: flex; flex-direction: column; justify-content: center;">
                    <label style="display: flex; align-items: center; gap: 0.6rem; cursor: pointer; margin-top: 1.2rem;">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} style="accent-color: var(--cheddar-bright);">
                        <span>Featured on Homepage</span>
                    </label>

                    <label style="display: flex; align-items: center; gap: 0.6rem; cursor: pointer; margin-top: 0.8rem;">
                        <input type="checkbox" name="is_available" value="1" checked style="accent-color: var(--cheddar-bright);">
                        <span>Immediately Available for Order</span>
                    </label>
                </div>
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--border-subtle);">
                <button type="submit" class="btn btn-primary btn-lg">
                    <span>Create Menu Item &rarr;</span>
                </button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary btn-lg">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
