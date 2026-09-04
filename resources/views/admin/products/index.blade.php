@extends('admin.layout')

@section('title', 'Produits')

@section('content')

    <div class="page-head">
        <p>{{ $products->total() }} produit(s) dans la boutique.</p>
        <a class="btn btn-primary" href="{{ route('admin.products.create') }}">➕ Ajouter un produit</a>
    </div>

    <form class="filters" method="GET" action="{{ route('admin.products.index') }}">
        <input class="input" type="search" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="🔍 Nom ou référence…">

        <select class="input" name="category" style="max-width:220px">
            <option value="">Toutes les catégories</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected(($filters['category'] ?? null) == $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>

        <select class="input" name="state" style="max-width:180px">
            <option value="">Tous les états</option>
            <option value="actifs" @selected(($filters['state'] ?? null) === 'actifs')>Visibles</option>
            <option value="inactifs" @selected(($filters['state'] ?? null) === 'inactifs')>Masqués</option>
        </select>

        <button class="btn btn-line" type="submit">Filtrer</button>
    </form>

    <div class="table-wrap">
        @if ($products->isEmpty() && $products->currentPage() > 1)
            <div class="empty-page">
                <span>📄</span>
                <p>Cette page ne contient plus d'éléments.</p>
                <a class="btn btn-primary btn-sm" style="margin-top:12px" href="{{ route('admin.products.index') }}">Revenir à la première page</a>
            </div>
        @elseif ($products->isEmpty())
            <div class="empty"><span>📦</span>Aucun produit ne correspond à cette recherche.</div>
        @else
            <table>
                <thead>
                <tr>
                    <th>Produit</th>
                    <th>Catégorie</th>
                    <th>Prix</th>
                    <th>Stock</th>
                    <th>État</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach ($products as $product)
                    <tr>
                        <td>
                            <div class="cell">
                                <span class="thumb" @if ($product->image) style="background-image:url('{{ media($product->image) }}')" @endif>
                                    @unless ($product->image){{ $product->emoji ?: '📦' }}@endunless
                                </span>
                                <span>
                                    <b>{{ $product->name }}</b>
                                    @if ($product->reference)<small>Réf. {{ $product->reference }}</small>@endif
                                </span>
                            </div>
                        </td>
                        <td>{{ $product->category?->name ?? '—' }}</td>
                        <td><b>{{ money($product->price) }}</b></td>
                        <td>
                            @if ($product->stock === null)
                                <span class="tag tag-grey">Non suivi</span>
                            @else
                                <span class="tag {{ $product->stock <= 0 ? 'tag-red' : 'tag-green' }}">{{ $product->stock }}</span>
                            @endif
                        </td>
                        <td>
                            <span class="tag {{ $product->is_active ? 'tag-green' : 'tag-grey' }}">
                                {{ $product->is_active ? 'Visible' : 'Masqué' }}
                            </span>
                            @if ($product->is_featured)<span class="tag tag-blue">Mis en avant</span>@endif
                        </td>
                        <td>
                            <div class="actions">
                                <a class="btn btn-line btn-sm" href="{{ route('admin.products.edit', $product) }}">Modifier</a>
                                @include('admin.partials.delete-form', ['action' => route('admin.products.destroy', $product), 'name' => $product->name])
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>

    {{ $products->links() }}

@endsection
