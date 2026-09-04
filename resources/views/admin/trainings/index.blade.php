@extends('admin.layout')

@section('title', 'Formations des techniciens')

@section('content')

    <div class="page-head">
        <p>Les formations suivies par vos équipes, au Sénégal comme à l'étranger.</p>
        <a class="btn btn-primary" href="{{ route('admin.trainings.create') }}">➕ Ajouter une formation</a>
    </div>

    <div class="table-wrap">
        @if ($trainings->isEmpty() && $trainings->currentPage() > 1)
            <div class="empty-page">
                <span>📄</span>
                <p>Cette page ne contient plus d'éléments.</p>
                <a class="btn btn-primary btn-sm" style="margin-top:12px" href="{{ route('admin.trainings.index') }}">Revenir à la première page</a>
            </div>
        @elseif ($trainings->isEmpty())
            <div class="empty"><span>🎓</span>Aucune formation enregistrée.</div>
        @else
            <table>
                <thead>
                <tr><th>Formation</th><th>Lieu</th><th>Année</th><th>Participants</th><th>Photos</th><th>État</th><th></th></tr>
                </thead>
                <tbody>
                @foreach ($trainings as $item)
                    <tr>
                        <td>
                            <div class="cell">
                                <span class="thumb" @if ($item->image) style="background-image:url('{{ media($item->image) }}')" @endif>
                                    @unless ($item->image) 🎓 @endunless
                                </span>
                                <span><b>{{ $item->title }}</b><small>{{ $item->organism }}</small></span>
                            </div>
                        </td>
                        <td>{{ $item->location() ?: '—' }}</td>
                        <td>{{ $item->year ?: '—' }}</td>
                        <td>{{ $item->participants ?: '—' }}</td>
                        <td>{{ $item->photos_count ?: '—' }}</td>
                        <td>
                            <span class="tag {{ $item->is_active ? 'tag-green' : 'tag-grey' }}">
                                {{ $item->is_active ? 'Visible' : 'Masqué' }}
                            </span>
                        </td>
                        <td>
                            <div class="actions">
                                <a class="btn btn-line btn-sm" href="{{ route('admin.trainings.edit', $item) }}">Modifier</a>
                                @include('admin.partials.delete-form', [
                                    'action' => route('admin.trainings.destroy', $item),
                                    'name' => $item->title,
                                ])
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>

    {{ $trainings->links() }}

@endsection
