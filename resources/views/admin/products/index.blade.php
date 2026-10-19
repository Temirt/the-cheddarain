@extends('layouts.admin')

@section('title', 'Menu Products — The Cheddarain Admin')

@section('admin_content')
<div>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h1 style="font-size: 2rem; margin-bottom: 0.3rem;">Menu & Products</h1>
            <p style="color: var(--text-cream-muted); font-size: 0.95rem;">Manage burger creations, loaded sides, signature sauces, and availability.</p>
        </div>

        <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">
            <span>+ Add New Product</span>
        </a>
    </div>

    <div class="data-table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Calories</th>
                    <th>Prep Time</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 1rem;">
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" style="width: 50px; height: 50px; border-radius: var(--radius-sm); object-fit: cover; background: var(--bg-surface);">
                                <div>
                                    <strong style="color: var(--text-cream); display: block;">{{ $product->name }}</strong>
                                    @if($product->badge)
                                        <span class="badge badge-cheddar" style="font-size: 0.68rem;">{{ $product->badge }}</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-gold">{{ $product->category->name ?? 'General' }}</span>
                        </td>
                        <td style="font-weight: 700; color: var(--gold-bright); font-size: 1.05rem;">
                            ${{ number_format($product->price, 2) }}
                        </td>
                        <td style="font-size: 0.88rem; color: var(--text-cream-muted);">
                            {{ $product->calories ? $product->calories . ' kcal' : '—' }}
                        </td>
                        <td style="font-size: 0.88rem;">
                            {{ $product->prep_time_minutes }} mins
                        </td>
                        <td>
                            <form action="{{ route('admin.products.toggle', $product->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="badge {{ $product->is_available ? 'badge-success' : 'badge-danger' }}" style="cursor: pointer; border: none;">
                                    {{ $product->is_available ? 'Available' : 'Out of Stock' }}
                                </button>
                            </form>
                        </td>
                        <td>
                            <div style="display: flex; gap: 0.5rem;">
                                <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-secondary btn-sm" style="padding: 0.35rem 0.75rem;">
                                    Edit
                                </a>
                                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Delete this menu item?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-secondary btn-sm" style="padding: 0.35rem 0.75rem; color: var(--danger);">
                                        ✕
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="margin-top: 1.5rem;">
        {{ $products->links() }}
    </div>
</div>
@endsection
