@extends('admin.layout')

@section('title', 'Agences et développement')

@section('content')

    <div class="page-head">
        <p>Vos agences actuelles et vos projets d'implantation.</p>
        <a class="btn btn-primary" href="{{ route('admin.agencies.create') }}">➕ Ajouter une agence</a>
    </div>

    <div class="table-wrap">
        @if ($agencies->isEmpty() && $agencies->currentPage() > 1)
            <div class="empty-page">
                <span>📄</span>
                <p>Cette page ne contient plus d'éléments.</p>
                <a class="btn btn-primary btn-sm" style="margin-top:12px" href="{{ route('admin.agencies.index') }}">Revenir à la première page</a>
            </div>
        @elseif ($agencies->isEmpty())
            <div class="empty"><span>📍</span>Aucune agence enregistrée.</div>
        @else
            <table>
                <thead>
                <tr><th>Agence</th><th>Région / Pays</th><th>Ouverture</th><th>Situation</th><th>État</th><th></th></tr>
                </thead>
                <tbody>
                @foreach ($agencies as $item)
                    <tr>
                        <td>
                            <div class="cell">
                                <span class="thumb" @if ($item->image) style="background-image:url('{{ media($item->image) }}')" @endif>
                                    @unless ($item->image) 📍 @endunless
                                </span>
                                <span><b>{{ $item->name }}</b><small>{{ $item->address }}</small></span>
                            </div>
                        </td>
                        <td>{{ collect([$item->region, $item->country])->filter()->implode(' · ') }}</td>
                        <td>{{ $item->opening_year ?: '—' }}</td>
                        <td><span class="tag tag-{{ $item->statusColor() }}">{{ $item->statusLabel() }}</span></td>
                        <td>
                            <span class="tag {{ $item->is_active ? 'tag-green' : 'tag-grey' }}">
                                {{ $item->is_active ? 'Visible' : 'Masqué' }}
                            </span>
                        </td>
                        <td>
                            <div class="actions">
                                <a class="btn btn-line btn-sm" href="{{ route('admin.agencies.edit', $item) }}">Modifier</a>
                                @include('admin.partials.delete-form', [
                                    'action' => route('admin.agencies.destroy', $item),
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

    {{ $agencies->links() }}

@endsection
