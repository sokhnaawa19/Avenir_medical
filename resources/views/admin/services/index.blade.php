@extends('admin.layout')

@section('title', 'Services')

@section('content')

    <div class="page-head">
        <p>Les services présentés sur l'accueil et la page « Services ».</p>
        <a class="btn btn-primary" href="{{ route('admin.services.create') }}">➕ Ajouter un service</a>
    </div>

    <div class="table-wrap">
        @if ($services->isEmpty() && $services->currentPage() > 1)
            <div class="empty-page">
                <span>📄</span>
                <p>Cette page ne contient plus d'éléments.</p>
                <a class="btn btn-primary btn-sm" style="margin-top:12px" href="{{ route('admin.services.index') }}">Revenir à la première page</a>
            </div>
        @elseif ($services->isEmpty())
            <div class="empty"><span>🛠️</span>Aucun service pour le moment.</div>
        @else
            <table>
                <thead>
                <tr><th>Service</th><th>Ordre</th><th>État</th><th></th></tr>
                </thead>
                <tbody>
                @foreach ($services as $service)
                    <tr>
                        <td>
                            <div class="cell">
                                <span class="thumb" @if ($service->image) style="background-image:url('{{ media($service->image) }}')" @endif>
                                    @unless ($service->image){{ $service->icon ?: '🛠️' }}@endunless
                                </span>
                                <span><b>{{ $service->title }}</b><small>{{ str($service->description)->limit(70) }}</small></span>
                            </div>
                        </td>
                        <td>{{ $service->position }}</td>
                        <td>
                            <span class="tag {{ $service->is_active ? 'tag-green' : 'tag-grey' }}">
                                {{ $service->is_active ? 'Visible' : 'Masqué' }}
                            </span>
                        </td>
                        <td>
                            <div class="actions">
                                <a class="btn btn-line btn-sm" href="{{ route('admin.services.edit', $service) }}">Modifier</a>
                                @include('admin.partials.delete-form', ['action' => route('admin.services.destroy', $service), 'name' => $service->title])
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>

    {{ $services->links() }}

@endsection
