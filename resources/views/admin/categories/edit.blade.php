@extends('layouts.admin')

@section('title', 'Edit Category — Admin Portal')

@section('admin_content')
    <div style="margin-bottom: 2rem;">
        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary btn-sm" style="margin-bottom: 1rem;">
            &larr; Back to Categories
        </a>
        <h1 style="font-size: 1.85rem; margin-bottom: 0.3rem;">Edit Category: {{ $category->name }}</h1>
        <p style="color: var(--text-cream-muted); font-size: 0.95rem;">Update category details and storefront ordering.</p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger" style="margin-bottom: 1.5rem;">
            <ul style="margin: 0; padding-left: 1.2rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="admin-card" style="max-width: 700px;">
        <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group" style="margin-bottom: 1.4rem;">
                <label class="form-label" for="name">Category Name *</label>
                <input type="text" name="name" id="name" class="form-input" value="{{ old('name', $category->name) }}" required>
            </div>

            <div class="form-group" style="margin-bottom: 1.4rem;">
                <label class="form-label" for="slug">URL Slug *</label>
                <input type="text" name="slug" id="slug" class="form-input" value="{{ old('slug', $category->slug) }}" required>
            </div>

            <div class="form-group" style="margin-bottom: 1.4rem;">
                <label class="form-label" for="icon">Icon / Emoji</label>
                <input type="text" name="icon" id="icon" class="form-input" value="{{ old('icon', $category->icon) }}">
            </div>

            <div class="form-group" style="margin-bottom: 1.4rem;">
                <label class="form-label" for="description">Short Description</label>
                <textarea name="description" id="description" class="form-input" rows="3">{{ old('description', $category->description) }}</textarea>
            </div>

            <div class="form-group" style="margin-bottom: 1.4rem;">
                <label class="form-label" for="sort_order">Display Sort Order</label>
                <input type="number" name="sort_order" id="sort_order" class="form-input" value="{{ old('sort_order', $category->sort_order) }}" min="1">
            </div>

            <div class="form-group" style="margin-bottom: 2rem;">
                <label style="display: flex; align-items: center; gap: 0.6rem; cursor: pointer; color: var(--text-cream);">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                    <span>Active and visible on the public website</span>
                </label>
            </div>

            <div style="display: flex; gap: 1rem;">
                <button type="submit" class="btn btn-primary">Update Category</button>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
@endsection
