@extends('admin.layout')

@section('title', "Domaines d'intervention")

@section('content')

    <div class="page-head">
        <p>Les grandes cartes photos affichées sur l'accueil et la page « Nos domaines ».</p>
        <a class="btn btn-primary" href="{{ route('admin.domains.create') }}">➕ Ajouter un domaine</a>
    </div>

    <div class="table-wrap">
        @if ($domains->isEmpty() && $domains->currentPage() > 1)
            <div class="empty-page">
                <span>📄</span>
                <p>Cette page ne contient plus d'éléments.</p>
                <a class="btn btn-primary btn-sm" style="margin-top:12px" href="{{ route('admin.domains.index') }}">Revenir à la première page</a>
            </div>
        @elseif ($domains->isEmpty())
            <div class="empty"><span>🏥</span>Aucun domaine pour le moment.</div>
        @else
            <table>
                <thead>
                <tr><th>Domaine</th><th>Ordre</th><th>Galerie</th><th>État</th><th></th></tr>
                </thead>
                <tbody>
                @foreach ($domains as $domain)
                    <tr>
                        <td>
                            <div class="cell">
                                <span class="thumb" @if ($domain->image) style="background-image:url('{{ media($domain->image) }}')" @endif>
                                    @unless ($domain->image)🏥@endunless
                                </span>
                                <span><b>{{ $domain->title }}</b><small>{{ $domain->subtitle }}</small></span>
                            </div>
                        </td>
                        <td>{{ $domain->position }}</td>
                        <td>
                            @if ($domain->in_gallery)
                                <span class="tag tag-blue">Dans la galerie</span>
                            @else
                                <span class="tag tag-grey">Non</span>
                            @endif
                        </td>
                        <td>
                            <span class="tag {{ $domain->is_active ? 'tag-green' : 'tag-grey' }}">
                                {{ $domain->is_active ? 'Visible' : 'Masqué' }}
                            </span>
                        </td>
                        <td>
                            <div class="actions">
                                <a class="btn btn-line btn-sm" href="{{ route('admin.domains.edit', $domain) }}">Modifier</a>
                                @include('admin.partials.delete-form', ['action' => route('admin.domains.destroy', $domain), 'name' => $domain->title])
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>

    {{ $domains->links() }}

@endsection
