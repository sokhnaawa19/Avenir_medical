@extends('layouts.public')

@section('title', 'Boutique en ligne — '.setting('site_name'))

@section('content')

    @include('partials.page-hero', [
        'title' => 'Boutique en ligne',
        'text' => 'Choisissez vos produits, ajoutez-les au panier et commandez : nous vous livrons.',
        'crumb' => 'Boutique',
    ])

    <section>
        <div class="wrap">
            @include('partials.flash')

            {{-- Filtres par categorie --}}
            <div class="shop-tools">
                <a class="pill @if (! $currentCategory) on @endif" href="{{ lroute('shop.index', request()->except(['categorie', 'page'])) }}">
                    {{ __('site.tout') }}
                </a>
                @foreach ($categories as $category)
                    <a class="pill @if ($currentCategory?->id === $category->id) on @endif"
                       href="{{ lroute('shop.index', array_merge(request()->except('page'), ['categorie' => $category->slug])) }}">
                        {{ $category->name }} ({{ $category->products_count }})
                    </a>
                @endforeach
            </div>

            {{-- Recherche et tri --}}
            <form class="shop-tools" method="GET" action="{{ lroute('shop.index') }}">
                @if ($currentCategory)
                    <input type="hidden" name="categorie" value="{{ $currentCategory->slug }}">
                @endif

                <input class="input search" type="search" name="q" value="{{ $search }}"
                       placeholder="{{ __('site.rechercher_un_produit') }}" aria-label="{{ __('site.rechercher_un_produit') }}">

                <select class="input" name="tri" style="max-width:230px" aria-label="{{ __('site.trier_les_produits') }}">
                    <option value="recent" @selected($sort === 'recent')>{{ __('site.tri_nos_preferences') }}</option>
                    <option value="prix-croissant" @selected($sort === 'prix-croissant')>{{ __('site.prix_croissant') }}</option>
                    <option value="prix-decroissant" @selected($sort === 'prix-decroissant')>{{ __('site.prix_decroissant') }}</option>
                    <option value="nom" @selected($sort === 'nom')>Nom (A → Z)</option>
                </select>

                <button class="btn btn-primary btn-sm" type="submit">{{ __('site.rechercher') }}</button>
            </form>

            @if ($products->isEmpty())
                <p class="vide"><span>@include('partials.icon', ['name' => 'search', 'size' => 58])</span>{{ __('site.aucun_produit_ne_correspond_a_votre_recherch') }}</p>
            @else
                <div class="grid-4">
                    @foreach ($products as $product)
                        @include('partials.product-card', ['product' => $product])
                    @endforeach
                </div>

                {{ $products->links() }}
            @endif
        </div>
    </section>

@endsection
