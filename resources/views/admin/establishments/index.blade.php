@extends('admin.layout')

@section('title', 'Références — établissements équipés')

@section('content')

    <div class="page-head">
        <p>Les hôpitaux, cliniques et centres de santé que vous avez équipés.</p>
        <a class="btn btn-primary" href="{{ route('admin.establishments.create') }}">➕ Ajouter une référence</a>
    </div>

    <div class="table-wrap">
        @if ($establishments->isEmpty() && $establishments->currentPage() > 1)
            <div class="empty-page">
                <span>📄</span>
                <p>Cette page ne contient plus d'éléments.</p>
                <a class="btn btn-primary btn-sm" style="margin-top:12px" href="{{ route('admin.establishments.index') }}">Revenir à la première page</a>
            </div>
        @elseif ($establishments->isEmpty())
            <div class="empty"><span>🏥</span>Aucune référence pour le moment.</div>
        @else
            <table>
                <thead>
                <tr><th>Établissement</th><th>Lieu</th><th>Année</th><th>Mise en avant</th><th>État</th><th></th></tr>
                </thead>
                <tbody>
                @foreach ($establishments as $item)
                    <tr>
                        <td>
                            <div class="cell">
                                <span class="thumb" style="background:#fff;border:1px solid var(--line)">
                                    @if ($item->logo)
                                        <img src="{{ media($item->logo) }}" alt="" style="max-width:38px;max-height:38px;object-fit:contain">
                                    @else
                                        {{ $item->initials() }}
                                    @endif
                                </span>
                                <span><b>{{ $item->name }}</b><small>{{ $item->type }}</small></span>
                            </div>
                        </td>
                        <td>{{ $item->location() ?: '—' }}</td>
                        <td>{{ $item->year ?: '—' }}</td>
                        <td>
                            @if ($item->is_flagship)<span class="tag tag-purple">Réalisation phare</span>@endif
                            @if ($item->is_featured)<span class="tag tag-blue">Accueil</span>@endif
                        </td>
                        <td>
                            <span class="tag {{ $item->is_active ? 'tag-green' : 'tag-grey' }}">
                                {{ $item->is_active ? 'Visible' : 'Masqué' }}
                            </span>
                        </td>
                        <td>
                            <div class="actions">
                                <a class="btn btn-line btn-sm" href="{{ route('admin.establishments.edit', $item) }}">Modifier</a>
                                @include('admin.partials.delete-form', [
                                    'action' => route('admin.establishments.destroy', $item),
                                    'name' => $item->name,
                                ])
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>

    {{ $establishments->links() }}

@endsection
