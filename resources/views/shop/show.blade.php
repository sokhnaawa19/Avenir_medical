@extends('layouts.public')

@section('title', $product->name.' — '.setting('site_name'))
@section('meta_description', $product->short_description ?: str($product->description)->limit(150))

@section('content')

    @include('partials.page-hero', [
        'title' => $product->name,
        'crumb' => '<a href="'.lroute('shop.index').'">'.__('site.boutique').'</a> › '.e($product->category?->name ?? 'Produit'),
    ])

    <section>
        <div class="wrap">
            @include('partials.flash')

            <div class="prod-page">
                <div class="photo">
                    @if ($product->image)
                        @include('partials.cover', [
                            'url' => media($product->image),
                            'alt' => $product->name,
                            'w' => 800, 'h' => 600, 'priority' => true,
                        ])
                    @else
                        @include('partials.icon-from', ['emoji' => $product->emoji ?: '📦', 'size' => 64])
                    @endif
                </div>

                <div>
                    <span class="eyebrow">{{ $product->category?->name }}</span>

                    @if ($product->brand || $product->domain)
                        <div class="brand-badges">
                            @if ($product->brand)
                                <span class="brand-badge">
                                    @if ($product->brand->logo)
                                        <img src="{{ media($product->brand->logo) }}" alt="{{ $product->brand->name }}"
                                             width="60" height="30" loading="lazy" decoding="async">
                                    @endif
                                    <b>{{ $product->brand->name }}</b>
                                </span>
                            @endif

                            @if ($product->domain)
                                <a class="brand-badge brand-badge--link" href="{{ lroute('domains') }}#{{ $product->domain->slug }}">
                                    @include('partials.icon', ['name' => 'hospital']) {{ $product->domain->title }}
                                </a>
                            @endif
                        </div>
                    @endif
                    <h2 style="font-family:Sora;font-size:1.9rem;color:var(--teal-dark)">{{ $product->name }}</h2>

                    @if ($product->reference)
                        <p class="text-muted" style="font-size:.88rem">Référence : {{ $product->reference }}</p>
                    @endif

                    <div class="price">
                        <span class="{{ $product->hasDiscount() ? 'price--promo' : '' }}">{{ money($product->sellingPrice()) }}</span>

                        @if ($product->isSoldByBox())
                            <span class="pack-detail">
                                <b>{{ $product->packagingLabel() }}</b>
                                <small>soit {{ money($product->price) }} l'unité</small>
                            </span>
                        @endif

                        @if ($product->hasDiscount())
                            <s class="price-old">{{ money($product->sellingOldPrice()) }}</s>
                            <span class="promo-tag">
                                −{{ (int) round(100 - ($product->price / $product->old_price * 100)) }} %
                            </span>
                        @endif
                    </div>

                    <p style="color:var(--muted);margin-bottom:26px">{{ $product->description ?: $product->short_description }}</p>

                    @if (settings()->boolean('show_stock') && $product->stock !== null)
                        <p class="{{ $product->isOutOfStock() ? 'stock-out' : 'text-muted' }}" style="margin-bottom:18px">
                            {{ $product->isOutOfStock() ? 'Produit momentanément épuisé' : $product->stock.' disponible(s) en stock' }}
                        </p>
                    @endif

                    @if ($product->isOutOfStock())
                        <a class="btn btn-line" href="{{ lroute('contact') }}">{{ __('site.nous_contacter_pour_ce_produit') }}</a>
                    @else
                        <form method="POST" action="{{ route('cart.store', $product) }}"
                              style="display:flex;gap:14px;flex-wrap:wrap;align-items:center">
                            @csrf

                            <div class="qty">
                                <button type="button" onclick="ajusterQuantite(-1)" aria-label="{{ __('site.diminuer') }}">−</button>
                                <input type="number" id="quantity" name="quantity" value="1" min="1" max="999" readonly>
                                <button type="button" onclick="ajusterQuantite(1)" aria-label="{{ __('site.augmenter') }}">+</button>
                            </div>

                            <button class="btn btn-primary" type="submit">@include('partials.icon', ['name' => 'cart']) {{ __('site.ajouter_au_panier') }}</button>
                        </form>
                    @endif

                    <p class="mt-3 text-muted" style="font-size:.88rem">
                        @include('partials.icon', ['name' => 'truck']) {{ setting('delivery_note') }}
                        @if (setting('phone_1'))
                            · @include('partials.icon', ['name' => 'phone']) Une question ? {{ setting('phone_1') }}
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </section>

    @if ($product->video_url)
        <section style="padding-top:0">
            <div class="wrap" style="max-width:900px">
                <div class="sec-head center">
                    <span class="eyebrow">{{ __('site.en_video') }}</span>
                    <h2>{{ __('site.le_produit_en_images') }}</h2>
                </div>

                @include('partials.video', [
                    'url' => $product->video_url,
                    'poster' => media($product->image),
                    'title' => $product->name,
                ])
            </div>
        </section>
    @endif

    @if ($related->isNotEmpty())
        <section style="padding-top:0">
            <div class="wrap">
                <div class="sec-head">
                    <span class="eyebrow">{{ __('site.vous_aimerez_aussi') }}</span>
                    <h2>{{ __('site.produits_similaires') }}</h2>
                </div>

                <div class="grid-4">
                    @foreach ($related as $item)
                        @include('partials.product-card', ['product' => $item])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

@endsection

@push('head')
    <script>
        // Boutons + et − de la quantite
        function ajusterQuantite(pas) {
            const champ = document.getElementById('quantity');
            const valeur = parseInt(champ.value, 10) || 1;
            champ.value = Math.min(999, Math.max(1, valeur + pas));
        }
    </script>
@endpush
