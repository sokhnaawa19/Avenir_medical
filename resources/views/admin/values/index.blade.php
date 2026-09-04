@extends('admin.layout')

@section('title', "Valeurs de l'entreprise")

@section('content')

    <div class="page-head">
        <p>Les valeurs affichées sur l'accueil et la page « Qui sommes-nous ».</p>
        <a class="btn btn-primary" href="{{ route('admin.values.create') }}">➕ Ajouter une valeur</a>
    </div>

    <div class="table-wrap">
        @if ($values->isEmpty() && $values->currentPage() > 1)
            <div class="empty-page">
                <span>📄</span>
                <p>Cette page ne contient plus d'éléments.</p>
                <a class="btn btn-primary btn-sm" style="margin-top:12px" href="{{ route('admin.values.index') }}">Revenir à la première page</a>
            </div>
        @elseif ($values->isEmpty())
            <div class="empty"><span>💎</span>Aucune valeur pour le moment.</div>
        @else
            <table>
                <thead>
                <tr><th>Valeur</th><th>Ordre</th><th>État</th><th></th></tr>
                </thead>
                <tbody>
                @foreach ($values as $value)
                    <tr>
                        <td>
                            <div class="cell">
                                <span class="thumb">{{ $value->icon ?: '💎' }}</span>
                                <span><b>{{ $value->title }}</b><small>{{ str($value->description)->limit(70) }}</small></span>
                            </div>
                        </td>
                        <td>{{ $value->position }}</td>
                        <td>
                            <span class="tag {{ $value->is_active ? 'tag-green' : 'tag-grey' }}">
                                {{ $value->is_active ? 'Visible' : 'Masquée' }}
                            </span>
                        </td>
                        <td>
                            <div class="actions">
                                <a class="btn btn-line btn-sm" href="{{ route('admin.values.edit', $value) }}">Modifier</a>
                                @include('admin.partials.delete-form', ['action' => route('admin.values.destroy', $value), 'name' => $value->title])
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>

    {{ $values->links() }}

@endsection
