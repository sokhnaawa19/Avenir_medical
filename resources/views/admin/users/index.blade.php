@extends('admin.layout')

@section('title', 'Comptes')

@section('content')

    <div class="page-head">
        <p>{{ $users->total() }} compte(s) : clients et administrateurs.</p>
        <a class="btn btn-primary" href="{{ route('admin.users.create') }}">➕ Créer un compte</a>
    </div>

    <form class="filters" method="GET" action="{{ route('admin.users.index') }}">
        <input class="input" type="search" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="🔍 Nom, email ou téléphone…">

        <select class="input" name="role" style="max-width:200px">
            <option value="">Tous les comptes</option>
            <option value="clients" @selected(($filters['role'] ?? null) === 'clients')>Clients</option>
            <option value="admins" @selected(($filters['role'] ?? null) === 'admins')>Administrateurs</option>
        </select>

        <button class="btn btn-line" type="submit">Filtrer</button>
    </form>

    <div class="table-wrap">
        @if ($users->isEmpty() && $users->currentPage() > 1)
            <div class="empty-page">
                <span>📄</span>
                <p>Cette page ne contient plus d'éléments.</p>
                <a class="btn btn-primary btn-sm" style="margin-top:12px" href="{{ route('admin.users.index') }}">Revenir à la première page</a>
            </div>
        @elseif ($users->isEmpty())
            <div class="empty"><span>👥</span>Aucun compte ne correspond à cette recherche.</div>
        @else
            <table>
                <thead>
                <tr><th>Compte</th><th>Téléphone</th><th>Commandes</th><th>Rôle</th><th></th></tr>
                </thead>
                <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td><b>{{ $user->name }}</b><br><small class="hint">{{ $user->email }}</small></td>
                        <td>{{ $user->phone ?: '—' }}</td>
                        <td>{{ $user->orders_count }}</td>
                        <td>
                            <span class="tag {{ $user->is_admin ? 'tag-blue' : 'tag-grey' }}">
                                {{ $user->is_admin ? 'Administrateur' : 'Client' }}
                            </span>
                        </td>
                        <td>
                            <div class="actions">
                                <a class="btn btn-line btn-sm" href="{{ route('admin.users.edit', $user) }}">Modifier</a>
                                @unless ($user->is(auth()->user()))
                                    @include('admin.partials.delete-form', ['action' => route('admin.users.destroy', $user), 'name' => $user->name])
                                @endunless
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>

    {{ $users->links() }}

@endsection
