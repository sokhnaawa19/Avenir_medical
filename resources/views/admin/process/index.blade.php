@extends('admin.layout')

@section('title', "Parcours d'accompagnement")

@section('content')

    <div class="page-head">
        <p>Les étapes qui expliquent comment vous transformez un besoin en solution.</p>
        <a class="btn btn-primary" href="{{ route('admin.process.create') }}">➕ Ajouter une étape</a>
    </div>

    <div class="table-wrap">
        @if ($steps->isEmpty() && $steps->currentPage() > 1)
            <div class="empty-page">
                <span>📄</span>
                <p>Cette page ne contient plus d'éléments.</p>
                <a class="btn btn-primary btn-sm" style="margin-top:12px" href="{{ route('admin.process.index') }}">Revenir à la première page</a>
            </div>
        @elseif ($steps->isEmpty())
            <div class="empty"><span>🧭</span>Aucune étape enregistrée.</div>
        @else
            <table>
                <thead>
                <tr><th>Ordre</th><th>Étape</th><th>État</th><th></th></tr>
                </thead>
                <tbody>
                @foreach ($steps as $index => $item)
                    <tr>
                        <td><b style="font-family:Sora;color:var(--teal);font-size:1.05rem">{{ str_pad($item->position + 1, 2, '0', STR_PAD_LEFT) }}</b></td>
                        <td>
                            <div class="cell">
                                <span class="thumb" @if ($item->image) style="background-image:url('{{ media($item->image) }}')" @endif>
                                    @unless ($item->image) {{ $item->icon ?: '🧭' }} @endunless
                                </span>
                                <span>
                                    <b>{{ $item->title }}</b>
                                    <small>{{ $item->subtitle }}</small>
                                </span>
                            </div>
                        </td>
                        <td>
                            <span class="tag {{ $item->is_active ? 'tag-green' : 'tag-grey' }}">
                                {{ $item->is_active ? 'Visible' : 'Masquée' }}
                            </span>
                        </td>
                        <td>
                            <div class="actions">
                                <a class="btn btn-line btn-sm" href="{{ route('admin.process.edit', $item) }}">Modifier</a>
                                @include('admin.partials.delete-form', [
                                    'action' => route('admin.process.destroy', $item),
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

    {{ $steps->links() }}

@endsection
