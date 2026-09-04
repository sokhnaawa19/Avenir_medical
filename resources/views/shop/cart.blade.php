@extends('layouts.public')

@section('title', 'Mon panier — '.setting('site_name'))

@section('content')

    @include('partials.page-hero', ['title' => 'Mon panier', 'crumb' => 'Panier'])

    <section>
        <div class="wrap">
            @include('partials.flash')

            @if ($items->isEmpty())
                <div class="vide">
                    <span>@include('partials.icon', ['name' => 'cart', 'size' => 58])</span>
                    <p>{{ __('site.votre_panier_est_vide_pour_le_moment') }}</p>
                    <a class="btn btn-primary mt-3" href="{{ lroute('shop.index') }}">{{ __('site.decouvrir_la_boutique') }}</a>
                </div>
            @else
                <div class="shop-layout">
                    <div>
                        @foreach ($items as $item)
                            <div class="cart-line">
                                <a class="ph" href="{{ lroute('shop.show', $item->product) }}">
                                    @if ($item->product->image)
                                        @include('partials.cover', ['url' => media($item->product->image), 'alt' => $item->product->name, 'w' => 160, 'h' => 160])
                                    @else
                                        @include('partials.icon-from', ['emoji' => $item->product->emoji ?: '📦', 'size' => 42])
                                    @endif
                                </a>

                                <div>
                                    <h3>{{ $item->product->name }}</h3>
                                    <small class="text-muted">
                                        {{ money($item->product->sellingPrice()) }}
                                        {{ $item->product->isSoldByBox() ? 'le carton' : "l'unité" }}
                                    </small>
                                </div>

                                <form method="POST" action="{{ lroute('cart.update', $item->product) }}" class="qty">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" name="quantity" value="{{ $item->quantity - 1 }}" aria-label="{{ __('site.diminuer') }}">−</button>
                                    <input type="text" value="{{ $item->quantity }}" readonly aria-label="{{ __('site.quantite') }}">
                                    <button type="submit" name="quantity" value="{{ $item->quantity + 1 }}" aria-label="{{ __('site.augmenter') }}">+</button>
                                </form>

                                <b style="font-family:Sora;color:var(--teal)">{{ money($item->total) }}</b>

                                <form method="POST" action="{{ route('cart.destroy', $item->product) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="sup" type="submit" title="{{ __('site.retirer_du_panier') }}">@include('partials.icon', ['name' => 'trash'])</button>
                                </form>
                            </div>
                        @endforeach

                        <form method="POST" action="{{ route('cart.clear') }}" class="mt-3">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-line btn-sm" type="submit">{{ __('site.vider_le_panier') }}</button>
                        </form>
                    </div>

                    <div class="totaux">
                        <h3 style="font-family:Sora;color:var(--teal-dark);margin-bottom:10px">{{ __('site.resume') }}</h3>

                        <div class="ligne"><span>{{ __('site.sous_total') }}</span><span>{{ money($subtotal) }}</span></div>
                        <div class="ligne">
                            <span>{{ __('site.livraison') }}</span>
                            <span>{{ $deliveryFee > 0 ? money($deliveryFee) : 'À convenir' }}</span>
                        </div>
                        <div class="ligne total"><span>{{ __('site.total') }}</span><span>{{ money($total) }}</span></div>

                        <a class="btn btn-primary btn-block mt-3" href="{{ route('checkout.create') }}">{{ __('site.passer_la_commande') }}</a>
                        <a class="link" style="display:block;text-align:center;margin-top:14px;font-size:.9rem" href="{{ lroute('shop.index') }}">
                            {{ __('site.continuer_mes_achats') }}
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </section>

@endsection
