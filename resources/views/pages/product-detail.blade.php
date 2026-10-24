@extends('layouts.app')

@section('title', $product->name . ' — The Cheddarloin')

@section('content')
<div class="container" style="padding-top: 3.5rem; padding-bottom: 5rem;">
    <!-- Breadcrumb -->
    <div style="margin-bottom: 2rem; font-size: 0.9rem; color: var(--text-cream-muted);">
        <a href="{{ route('home') }}">Home</a> &rarr; 
        <a href="{{ route('menu') }}">Menu</a> &rarr; 
        <a href="{{ route('menu', ['category' => $product->category->slug]) }}">{{ $product->category->name }}</a> &rarr; 
        <span style="color: var(--text-cream);">{{ $product->name }}</span>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1.15fr; gap: 3.5rem; align-items: start;">
        <!-- Product Image -->
        <div>
            <div style="border-radius: var(--radius-xl); overflow: hidden; border: 1px solid var(--border-subtle); box-shadow: var(--shadow-lg); background: var(--bg-card);">
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" style="width: 100%; height: 460px; object-fit: cover;">
            </div>

            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-top: 1.5rem;">
                <div style="background: var(--bg-surface); padding: 1rem; border-radius: var(--radius-md); text-align: center; border: 1px solid var(--border-subtle);">
                    <div style="font-size: 0.8rem; color: var(--text-cream-muted);">Prep Time</div>
                    <div style="font-weight: 700; color: var(--gold-bright); font-size: 1.1rem;">~{{ $product->prep_time_minutes }} mins</div>
                </div>
                <div style="background: var(--bg-surface); padding: 1rem; border-radius: var(--radius-md); text-align: center; border: 1px solid var(--border-subtle);">
                    <div style="font-size: 0.8rem; color: var(--text-cream-muted);">Calories</div>
                    <div style="font-weight: 700; color: var(--gold-bright); font-size: 1.1rem;">{{ $product->calories ?? 'N/A' }} kcal</div>
                </div>
                <div style="background: var(--bg-surface); padding: 1rem; border-radius: var(--radius-md); text-align: center; border: 1px solid var(--border-subtle);">
                    <div style="font-size: 0.8rem; color: var(--text-cream-muted);">Quality</div>
                    <div style="font-weight: 700; color: var(--cheddar-bright); font-size: 1.1rem;">100% Angus</div>
                </div>
            </div>
        </div>

        <!-- Product Details & Customization -->
        <div style="background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-xl); padding: 2.5rem;">
            <div style="display: flex; gap: 0.6rem; margin-bottom: 0.8rem;">
                <span class="badge badge-cheddar">{{ $product->category->name }}</span>
                @if($product->badge)
                    <span class="badge badge-gold">{{ $product->badge }}</span>
                @endif
            </div>

            <h1 style="font-size: 2.2rem; margin-bottom: 0.8rem;">{{ $product->name }}</h1>
            <p style="font-size: 1.05rem; color: var(--text-cream-soft); line-height: 1.6; margin-bottom: 1.8rem;">
                {{ $product->description }}
            </p>

            <div style="display: flex; align-items: baseline; gap: 1rem; padding-bottom: 1.5rem; border-bottom: 1px solid var(--border-subtle); margin-bottom: 2rem;">
                <span style="font-size: 0.9rem; color: var(--text-cream-muted);">Base Price:</span>
                <span style="font-size: 2rem; font-weight: 800; font-family: var(--font-heading); color: var(--gold-bright);" id="detail-base-price">{{ number_format($product->price, 2) }} Birr</span>
            </div>

            <!-- Customizations -->
            <form id="product-detail-form">
                <input type="hidden" id="detail-product-id" value="{{ $product->id }}">
                <input type="hidden" id="detail-product-name" value="{{ $product->name }}">
                <input type="hidden" id="detail-product-base" value="{{ $product->price }}">
                <input type="hidden" id="detail-product-image" value="{{ $product->image_url }}">

                @foreach($product->options as $option)
                    <div class="option-group" data-option-id="{{ $option->id }}" data-option-name="{{ $option->name }}" style="margin-bottom: 1.8rem;">
                        <div class="option-group-title">
                            <span>{{ $option->name }}</span>
                            @if($option->is_required)
                                <span class="badge badge-cheddar" style="font-size: 0.68rem;">Required</span>
                            @else
                                <span style="font-size: 0.78rem; color: var(--text-dim);">Optional</span>
                            @endif
                        </div>

                        @foreach($option->values as $val)
                            <label class="option-item-row">
                                <div class="option-label-info">
                                    <input type="{{ $option->type === 'radio' ? 'radio' : 'checkbox' }}"
                                           name="detail_opt_{{ $option->id }}"
                                           value="{{ $val->id }}"
                                           data-price="{{ $val->price }}"
                                           data-val-name="{{ $val->name }}"
                                           {{ $val->is_default ? 'checked' : '' }}>
                                    <span>{{ $val->name }}</span>
                                </div>
                                <span class="badge badge-gold" style="font-size: 0.75rem;">
                                    {{ $val->price > 0 ? '(+' . number_format($val->price, 2) . ' Birr)' : 'Included' }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                @endforeach

                <!-- Quantity & Add to Cart -->
                <div style="display: flex; align-items: center; gap: 1.5rem; margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--border-subtle);">
                    <div class="qty-control" style="background: var(--bg-surface); padding: 0.3rem;">
                        <button type="button" class="qty-btn" id="detail-qty-minus">-</button>
                        <span class="qty-value" id="detail-qty-value" style="padding: 0 0.8rem;">1</span>
                        <button type="button" class="qty-btn" id="detail-qty-plus">+</button>
                    </div>

                    <button type="button" class="btn btn-primary btn-lg" id="detail-add-btn" style="flex: 1;">
                        Add to Cart • <span id="detail-total-price">{{ number_format($product->price, 2) }} Birr</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    let quantity = 1;
    const basePrice = parseFloat(document.getElementById('detail-product-base').value);
    const form = document.getElementById('product-detail-form');
    const qtyValEl = document.getElementById('detail-qty-value');
    const totalEl = document.getElementById('detail-total-price');

    function calculateTotal() {
        let optionsPrice = 0;
        form.querySelectorAll('input:checked').forEach(input => {
            optionsPrice += parseFloat(input.dataset.price) || 0;
        });
        const unit = basePrice + optionsPrice;
        const total = unit * quantity;
        totalEl.textContent = Cart.formatPrice(total);
        return { unit, total };
    }

    document.getElementById('detail-qty-minus').addEventListener('click', () => {
        if (quantity > 1) {
            quantity--;
            qtyValEl.textContent = quantity;
            calculateTotal();
        }
    });

    document.getElementById('detail-qty-plus').addEventListener('click', () => {
        if (quantity < 50) {
            quantity++;
            qtyValEl.textContent = quantity;
            calculateTotal();
        }
    });

    form.querySelectorAll('input').forEach(input => {
        input.addEventListener('change', calculateTotal);
    });

    document.getElementById('detail-add-btn').addEventListener('click', () => {
        const selectedOptions = [];
        form.querySelectorAll('.option-group').forEach(group => {
            const optName = group.dataset.optionName;
            group.querySelectorAll('input:checked').forEach(input => {
                selectedOptions.push({
                    option_name: optName,
                    value_id: parseInt(input.value),
                    choice: input.dataset.valName,
                    price: parseFloat(input.dataset.price) || 0
                });
            });
        });

        const product = {
            id: parseInt(document.getElementById('detail-product-id').value),
            name: document.getElementById('detail-product-name').value,
            price: basePrice,
            image_url: document.getElementById('detail-product-image').value
        };

        Cart.addItem(product, quantity, selectedOptions);
    });
});
</script>
@endpush
@endsection
