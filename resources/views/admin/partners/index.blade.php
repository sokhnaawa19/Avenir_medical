@extends('admin.layout')

@section('title', 'Partenaires')

@section('content')

    <div class="page-head">
        <p>Les entreprises partenaires affichées sur l'accueil, la page Services et la page Partenaires.</p>
        <a class="btn btn-primary" href="{{ route('admin.partners.create') }}">➕ Ajouter un partenaire</a>
    </div>

    <div class="table-wrap">
        @if ($partners->isEmpty() && $partners->currentPage() > 1)
            <div class="empty-page">
                <span>📄</span>
                <p>Cette page ne contient plus d'éléments.</p>
                <a class="btn btn-primary btn-sm" style="margin-top:12px" href="{{ route('admin.partners.index') }}">Revenir à la première page</a>
            </div>
        @elseif ($partners->isEmpty())
            <div class="empty"><span>🤝</span>Aucun partenaire pour le moment.</div>
        @else
            <table>
                <thead>
                <tr><th>Partenaire</th><th>Pays</th><th>Ordre</th><th>Accueil</th><th>État</th><th></th></tr>
                </thead>
                <tbody>
                @foreach ($partners as $partner)
                    <tr>
                        <td>
                            <div class="cell">
                                <span class="thumb" style="background:#fff;border:1px solid var(--line)">
                                    @if ($partner->logo)
                                        <img src="{{ media($partner->logo) }}" alt="{{ $partner->name }}"
                                             style="max-width:38px;max-height:38px;object-fit:contain">
                                    @else
                                        {{ $partner->initials() }}
                                    @endif
                                </span>
                                <span>
                                    <b>{{ $partner->name }}</b>
                                    @if ($partner->website)<small>{{ $partner->website }}</small>@endif
                                </span>
                            </div>
                        </td>
                        <td>{{ $partner->country ?: '—' }}</td>
                        <td>{{ $partner->position }}</td>
                        <td>
                            @if ($partner->is_exclusive)
                                <span class="tag tag-purple">⭐ Exclusif</span>
                            @endif
                            @if ($partner->is_featured)
                                <span class="tag tag-blue">Affiché</span>
                            @else
                                <span class="tag tag-grey">Non</span>
                            @endif
                        </td>
                        <td>
                            <span class="tag {{ $partner->is_active ? 'tag-green' : 'tag-grey' }}">
                                {{ $partner->is_active ? 'Visible' : 'Masqué' }}
                            </span>
                        </td>
                        <td>
                            <div class="actions">
                                <a class="btn btn-line btn-sm" href="{{ route('admin.partners.edit', $partner) }}">Modifier</a>
                                @include('admin.partials.delete-form', ['action' => route('admin.partners.destroy', $partner), 'name' => $partner->name])
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>

    {{ $partners->links() }}

@endsection
