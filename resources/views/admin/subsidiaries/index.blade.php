@extends('admin.layout')

@section('title', 'Les entreprises du groupe')

@section('content')

    <div class="page-head">
        <p>Les sociétés du groupe, dont AVENIR MEDICAL est la maison mère.</p>
        <a class="btn btn-primary" href="{{ route('admin.subsidiaries.create') }}">➕ Ajouter une entreprise</a>
    </div>

    <div class="table-wrap">
        @if ($subsidiaries->isEmpty() && $subsidiaries->currentPage() > 1)
            <div class="empty-page">
                <span>📄</span>
                <p>Cette page ne contient plus d'éléments.</p>
                <a class="btn btn-primary btn-sm" style="margin-top:12px" href="{{ route('admin.subsidiaries.index') }}">Revenir à la première page</a>
            </div>
        @elseif ($subsidiaries->isEmpty())
            <div class="empty"><span>🏢</span>Aucune entreprise enregistrée.</div>
        @else
            <table>
                <thead>
                <tr><th>Entreprise</th><th>Activité</th><th>Création</th><th>Ordre</th><th>État</th><th></th></tr>
                </thead>
                <tbody>
                @foreach ($subsidiaries as $item)
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
                                <span><b>{{ $item->name }}</b><small>{{ $item->tagline }}</small></span>
                            </div>
                        </td>
                        <td>{{ $item->activity ?: '—' }}</td>
                        <td>{{ $item->founded_year ?: '—' }}</td>
                        <td>{{ $item->position }}</td>
                        <td>
                            <span class="tag {{ $item->is_active ? 'tag-green' : 'tag-grey' }}">
                                {{ $item->is_active ? 'Visible' : 'Masqué' }}
                            </span>
                        </td>
                        <td>
                            <div class="actions">
                                <a class="btn btn-line btn-sm" href="{{ route('admin.subsidiaries.edit', $item) }}">Modifier</a>
                                @include('admin.partials.delete-form', [
                                    'action' => route('admin.subsidiaries.destroy', $item),
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

    {{ $subsidiaries->links() }}

@endsection
