@extends('admin.layout')

@section('title', 'Galerie photos')

@section('content')

    <div class="page-head">
        <p>Les photos des événements, installations et formations.</p>
        <a class="btn btn-primary" href="{{ route('admin.photos.create') }}">➕ Ajouter une photo</a>
    </div>

    <div class="table-wrap">
        @if ($photos->isEmpty() && $photos->currentPage() > 1)
            <div class="empty-page">
                <span>📄</span>
                <p>Cette page ne contient plus d'éléments.</p>
                <a class="btn btn-primary btn-sm" style="margin-top:12px" href="{{ route('admin.photos.index') }}">Revenir à la première page</a>
            </div>
        @elseif ($photos->isEmpty())
            <div class="empty"><span>📷</span>Aucune photo dans la galerie.</div>
        @else
            <table>
                <thead>
                <tr><th>Photo</th><th>Album</th><th>Date</th><th>Ordre</th><th>État</th><th></th></tr>
                </thead>
                <tbody>
                @foreach ($photos as $item)
                    <tr>
                        <td>
                            <div class="cell">
                                <span class="thumb" style="background-image:url('{{ media($item->image) }}')"></span>
                                <span><b>{{ $item->title ?: 'Sans titre' }}</b><small>{{ str($item->caption)->limit(50) }}</small></span>
                            </div>
                        </td>
                        <td>@if ($item->album)<span class="tag">{{ $item->album }}</span>@endif</td>
                        <td>{{ $item->taken_at ?: '—' }}</td>
                        <td>{{ $item->position }}</td>
                        <td>
                            <span class="tag {{ $item->is_active ? 'tag-green' : 'tag-grey' }}">
                                {{ $item->is_active ? 'Visible' : 'Masqué' }}
                            </span>
                        </td>
                        <td>
                            <div class="actions">
                                <a class="btn btn-line btn-sm" href="{{ route('admin.photos.edit', $item) }}">Modifier</a>
                                @include('admin.partials.delete-form', [
                                    'action' => route('admin.photos.destroy', $item),
                                    'name' => $item->title ?: 'cette photo',
                                ])
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>

    {{ $photos->links() }}

@endsection
