{{-- Carte d'un produit dans la boutique. Variable : $product --}}
<div class="prod">
    <a class="ph" href="{{ lroute('shop.show', $product) }}">
        @if ($product->image)
            @include('partials.cover', ['url' => media($product->image), 'alt' => $product->name, 'w' => 400, 'h' => 300])
        @else
            @include('partials.icon-from', ['emoji' => $product->emoji ?: '📦', 'size' => 48])
        @endif

        {{-- Pastille de promotion, avec le pourcentage de réduction --}}
        @if ($product->hasDiscount())
            <span class="promo-badge">−{{ (int) round(100 - ($product->price / $product->old_price * 100)) }} %</span>
        @endif
    </a>

    <div class="body">
        <span class="cat">{{ $product->category?->name ?? 'Produit' }}</span>
        @if ($product->brand)
            <span class="prod-brand">{{ $product->brand->name }}</span>
        @endif
        <h3><a href="{{ lroute('shop.show', $product) }}">{{ $product->name }}</a></h3>

        @if (settings()->boolean('show_stock') && $product->stock !== null)
            <small class="{{ $product->isOutOfStock() ? 'stock-out' : 'text-muted' }}">
                {{ $product->isOutOfStock() ? 'Rupture de stock' : $product->stock.' en stock' }}
            </small>
        @endif

        <div class="row">
            <span class="price-block">
                <span class="price {{ $product->hasDiscount() ? 'price--promo' : '' }}">{{ money($product->sellingPrice()) }}</span>

                @if ($product->isSoldByBox())
                    <span class="pack-label">{{ $product->packagingLabel() }}</span>
                @endif

                @if ($product->hasDiscount())
                    <s class="price-old">{{ money($product->sellingOldPrice()) }}</s>
                @endif
            </span>

            @if ($product->isOutOfStock())
                <span class="badge-soft badge-red">{{ __('site.epuise') }}</span>
            @else
                <form method="POST" action="{{ route('cart.store', $product) }}">
                    @csrf
                    <button class="add" type="submit" title="{{ __('site.ajouter_au_panier') }}">+</button>
                </form>
            @endif
        </div>
    </div>
</div>
