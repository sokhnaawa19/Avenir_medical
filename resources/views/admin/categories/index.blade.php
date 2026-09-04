@extends('admin.layout')

@section('title', 'Catégories de produits')

@section('content')

    <div class="page-head">
        <p>Les catégories servent de filtres dans la boutique.</p>
        <a class="btn btn-primary" href="{{ route('admin.categories.create') }}">➕ Ajouter une catégorie</a>
    </div>

    <div class="table-wrap">
        @if ($categories->isEmpty() && $categories->currentPage() > 1)
            <div class="empty-page">
                <span>📄</span>
                <p>Cette page ne contient plus d'éléments.</p>
                <a class="btn btn-primary btn-sm" style="margin-top:12px" href="{{ route('admin.categories.index') }}">Revenir à la première page</a>
            </div>
        @elseif ($categories->isEmpty())
            <div class="empty"><span>🗂️</span>Aucune catégorie pour le moment.</div>
        @else
            <table>
                <thead>
                <tr><th>Catégorie</th><th>Produits</th><th>Ordre</th><th>État</th><th></th></tr>
                </thead>
                <tbody>
                @foreach ($categories as $category)
                    <tr>
                        <td>
                            <div class="cell">
                                <span class="thumb" @if ($category->image) style="background-image:url('{{ media($category->image) }}')" @endif>
                                    @unless ($category->image)🗂️@endunless
                                </span>
                                <span><b>{{ $category->name }}</b><small>{{ $category->slug }}</small></span>
                            </div>
                        </td>
                        <td>{{ $category->products_count }}</td>
                        <td>{{ $category->position }}</td>
                        <td>
                            <span class="tag {{ $category->is_active ? 'tag-green' : 'tag-grey' }}">
                                {{ $category->is_active ? 'Visible' : 'Masquée' }}
                            </span>
                        </td>
                        <td>
                            <div class="actions">
                                <a class="btn btn-line btn-sm" href="{{ route('admin.categories.edit', $category) }}">Modifier</a>
                                @include('admin.partials.delete-form', ['action' => route('admin.categories.destroy', $category), 'name' => $category->name])
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>

    {{ $categories->links() }}

@endsection
