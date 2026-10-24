@extends('layouts.admin')

@section('title', 'Manage Categories — Admin Portal')

@section('admin_content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h1 style="font-size: 1.85rem; margin-bottom: 0.3rem;">Menu Categories</h1>
            <p style="color: var(--text-cream-muted); font-size: 0.95rem;">Organize your menu into categories (Burgers, Sides, Drinks, Meals, etc.).</p>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
            <span>+</span>
            <span>Add New Category</span>
        </a>
    </div>

    <div class="admin-table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Icon</th>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Products Count</th>
                    <th>Status</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                    <tr>
                        <td style="font-weight: 700; color: var(--gold-bright);">{{ $category->sort_order }}</td>
                        <td style="font-size: 1.5rem;">{{ $category->icon ?? '🍔' }}</td>
                        <td>
                            <strong style="color: var(--text-cream);">{{ $category->name }}</strong>
                            @if($category->description)
                                <div style="font-size: 0.8rem; color: var(--text-dim);">{{ Str::limit($category->description, 60) }}</div>
                            @endif
                        </td>
                        <td><code style="background: rgba(255,255,255,0.06); padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.85rem;">{{ $category->slug }}</code></td>
                        <td>
                            <span class="badge badge-gold">{{ $category->products_count }} items</span>
                        </td>
                        <td>
                            @if($category->is_active)
                                <span class="badge" style="background: rgba(111, 175, 117, 0.2); color: var(--success); border: 1px solid var(--success);">Active</span>
                            @else
                                <span class="badge" style="background: rgba(255, 255, 255, 0.1); color: var(--text-dim);">Hidden</span>
                            @endif
                        </td>
                        <td style="text-align: right;">
                            <div style="display: inline-flex; gap: 0.5rem;">
                                <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-secondary btn-sm" style="padding: 0.35rem 0.75rem;">
                                    Edit
                                </a>
                                @if($category->products_count === 0)
                                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this category?');" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" style="padding: 0.35rem 0.75rem;">
                                            Delete
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 3rem; color: var(--text-dim);">
                            No categories created yet. Click "+ Add New Category" above to create one.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
