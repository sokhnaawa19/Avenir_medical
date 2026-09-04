@extends('admin.layout')

@section('title', "Historique de l'entreprise")

@section('content')

    <div class="page-head">
        <p>Les grandes étapes affichées sur la page « Qui sommes-nous ».</p>
        <a class="btn btn-primary" href="{{ route('admin.milestones.create') }}">➕ Ajouter une étape</a>
    </div>

    <div class="table-wrap">
        @if ($milestones->isEmpty() && $milestones->currentPage() > 1)
            <div class="empty-page">
                <span>📄</span>
                <p>Cette page ne contient plus d'éléments.</p>
                <a class="btn btn-primary btn-sm" style="margin-top:12px" href="{{ route('admin.milestones.index') }}">Revenir à la première page</a>
            </div>
        @elseif ($milestones->isEmpty())
            <div class="empty"><span>📜</span>Aucune étape pour le moment.</div>
        @else
            <table>
                <thead>
                <tr><th>Année</th><th>Étape</th><th>Ordre</th><th>État</th><th></th></tr>
                </thead>
                <tbody>
                @foreach ($milestones as $milestone)
                    <tr>
                        <td><b style="font-family:Sora;color:var(--teal);font-size:1.05rem">{{ $milestone->year }}</b></td>
                        <td>
                            <div class="cell">
                                <span class="thumb" @if ($milestone->image) style="background-image:url('{{ media($milestone->image) }}')" @endif>
                                    @unless ($milestone->image) 📜 @endunless
                                </span>
                                <span>
                                    <b>{{ $milestone->title }}</b>
                                    <small>{{ str($milestone->description)->limit(70) }}</small>
                                </span>
                            </div>
                        </td>
                        <td>{{ $milestone->position }}</td>
                        <td>
                            <span class="tag {{ $milestone->is_active ? 'tag-green' : 'tag-grey' }}">
                                {{ $milestone->is_active ? 'Visible' : 'Masquée' }}
                            </span>
                        </td>
                        <td>
                            <div class="actions">
                                <a class="btn btn-line btn-sm" href="{{ route('admin.milestones.edit', $milestone) }}">Modifier</a>
                                @include('admin.partials.delete-form', ['action' => route('admin.milestones.destroy', $milestone), 'name' => $milestone->year.' — '.$milestone->title])
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>

    {{ $milestones->links() }}

@endsection
