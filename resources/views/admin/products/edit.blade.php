@extends('layouts.admin')

@section('title', 'Edit ' . $product->name . ' — The Cheddarain Admin')

@section('admin_content')
<div style="max-width: 800px;">
    <div style="margin-bottom: 2rem;">
        <h1 style="font-size: 2rem; margin-bottom: 0.3rem;">Edit: {{ $product->name }}</h1>
        <p style="color: var(--text-cream-muted); font-size: 0.95rem;">Update pricing, description, imagery, or availability.</p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger" style="margin-bottom: 1.5rem;">
            <span>✕</span>
            <span>{{ $errors->first() }}</span>
        </div>
    @endif

    <div style="background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-lg); padding: 2.2rem;">
        <form action="{{ route('admin.products.update', $product->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.2rem;">
                <div class="form-group" style="grid-column: 1 / -1;">
                    <label class="form-label" for="name">Product Name *</label>
                    <input type="text" name="name" id="name" class="form-input" required value="{{ old('name', $product->name) }}">
                </div>

                <div class="form-group">
                    <label class="form-label" for="category_id">Category *</label>
                    <select name="category_id" id="category_id" class="form-select" required>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="price">Price ($) *</label>
                    <input type="number" step="0.01" name="price" id="price" class="form-input" required value="{{ old('price', $product->price) }}">
                </div>

                <div class="form-group" style="grid-column: 1 / -1;">
                    <label class="form-label" for="description">Product Description *</label>
                    <textarea name="description" id="description" class="form-textarea" required>{{ old('description', $product->description) }}</textarea>
                </div>

                <div class="form-group" style="grid-column: 1 / -1;">
                    <label class="form-label" for="image_url">Image URL</label>
                    <input type="url" name="image_url" id="image_url" class="form-input" value="{{ old('image_url', $product->image_url) }}">
                </div>

                <div class="form-group">
                    <label class="form-label" for="badge">Promo Badge (Optional)</label>
                    <input type="text" name="badge" id="badge" class="form-input" value="{{ old('badge', $product->badge) }}">
                </div>

                <div class="form-group">
                    <label class="form-label" for="calories">Calories (kcal)</label>
                    <input type="number" name="calories" id="calories" class="form-input" value="{{ old('calories', $product->calories) }}">
                </div>

                <div class="form-group">
                    <label class="form-label" for="prep_time_minutes">Kitchen Prep Time (Minutes) *</label>
                    <input type="number" name="prep_time_minutes" id="prep_time_minutes" class="form-input" required value="{{ old('prep_time_minutes', $product->prep_time_minutes) }}">
                </div>

                <div class="form-group" style="display: flex; flex-direction: column; justify-content: center;">
                    <label style="display: flex; align-items: center; gap: 0.6rem; cursor: pointer; margin-top: 1.2rem;">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }} style="accent-color: var(--cheddar-bright);">
                        <span>Featured on Homepage</span>
                    </label>

                    <label style="display: flex; align-items: center; gap: 0.6rem; cursor: pointer; margin-top: 0.8rem;">
                        <input type="checkbox" name="is_available" value="1" {{ old('is_available', $product->is_available) ? 'checked' : '' }} style="accent-color: var(--cheddar-bright);">
                        <span>Available for Order</span>
                    </label>
                </div>
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--border-subtle);">
                <button type="submit" class="btn btn-primary btn-lg">
                    <span>Save Changes &rarr;</span>
                </button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary btn-lg">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
