@extends('admin.layout')

@section('title', 'Conditionnement — prix par carton')

@section('content')

    <div class="page-head">
        <p>
            Indiquez combien d'unités contient un carton. Le site affichera alors
            le prix du carton, calculé automatiquement à partir du prix unitaire.
            Laissez vide pour les produits vendus à l'unité.
        </p>
    </div>

    @if ($restants > 0)
        <div class="alert alert-error" style="margin-bottom:18px">
            {{ $restants }} produit(s) n'ont pas encore de conditionnement : ils sont vendus à l'unité.
        </div>
    @endif

    {{-- Filtres --}}
    <div class="shop-tools" style="margin-bottom:18px">
        <a class="pill @if ($etat === 'tous') on @endif"
           href="{{ route('admin.packaging.index', ['category' => $currentCategory]) }}">Tous</a>
        <a class="pill @if ($etat === 'a-remplir') on @endif"
           href="{{ route('admin.packaging.index', ['etat' => 'a-remplir', 'category' => $currentCategory]) }}">À remplir</a>
        <a class="pill @if ($etat === 'remplis') on @endif"
           href="{{ route('admin.packaging.index', ['etat' => 'remplis', 'category' => $currentCategory]) }}">Déjà remplis</a>

        <span style="width:100%;height:0"></span>

        <a class="pill @if (! $currentCategory) on @endif"
           href="{{ route('admin.packaging.index', ['etat' => $etat]) }}">Toutes catégories</a>
        @foreach ($categories as $category)
            <a class="pill @if ($currentCategory == $category->id) on @endif"
               href="{{ route('admin.packaging.index', ['category' => $category->id, 'etat' => $etat]) }}">
                {{ $category->name }}
            </a>
        @endforeach
    </div>

    <form method="POST" action="{{ route('admin.packaging.update') }}">
        @csrf
        @method('PUT')

        <div class="table-wrap">
            @if ($products->isEmpty())
                <div class="empty"><span>@include('partials.icon', ['name' => 'box', 'size' => 36])</span>Aucun produit dans cette sélection.</div>
            @else
                <table>
                    <thead>
                    <tr>
                        <th>Produit</th>
                        <th style="width:130px">Prix unitaire</th>
                        <th style="width:150px">Unités / carton</th>
                        <th style="width:150px">Nom (facultatif)</th>
                        <th style="width:150px">Prix du carton</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <td>
                                <div class="cell">
                                    <span class="thumb" @if ($product->image) style="background-image:url('{{ media($product->image) }}')" @endif></span>
                                    <span>
                                        <b>{{ $product->name }}</b>
                                        <small>{{ $product->category?->name }}</small>
                                    </span>
                                </div>
                            </td>

                            <td><b>{{ money($product->price) }}</b></td>

                            <td>
                                <input class="input pack-input"
                                       type="number"
                                       name="units[{{ $product->id }}]"
                                       value="{{ $product->units_per_box }}"
                                       min="1" placeholder="—"
                                       data-prix="{{ $product->price }}">
                            </td>

                            <td>
                                <input class="input"
                                       type="text"
                                       name="labels[{{ $product->id }}]"
                                       value="{{ $product->box_label }}"
                                       placeholder="Carton" maxlength="40">
                            </td>

                            <td>
                                <b class="pack-total" data-produit="{{ $product->id }}">
                                    {{ $product->isSoldByBox() ? money($product->sellingPrice()) : '—' }}
                                </b>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        @if ($products->isNotEmpty())
            <div class="form-actions"
                 style="position:sticky;bottom:0;background:#fff;padding:16px;border-radius:14px;box-shadow:0 -6px 20px rgba(12,59,76,.08)">
                <button class="btn btn-primary" type="submit">💾 Enregistrer tout</button>
                <span style="color:var(--muted);font-size:.88rem">
                    {{ $products->count() }} produit(s) affiché(s)
                </span>
            </div>
        @endif
    </form>

@endsection
