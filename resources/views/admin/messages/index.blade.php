@extends('admin.layout')

@section('title', 'Messages reçus')

@section('content')

    <div class="page-head">
        <p>{{ $messages->total() }} message(s) envoyé(s) depuis le formulaire de contact.</p>
    </div>

    <div class="table-wrap">
        @if ($messages->isEmpty() && $messages->currentPage() > 1)
            <div class="empty-page">
                <span>📄</span>
                <p>Cette page ne contient plus d'éléments.</p>
                <a class="btn btn-primary btn-sm" style="margin-top:12px" href="{{ route('admin.messages.index') }}">Revenir à la première page</a>
            </div>
        @elseif ($messages->isEmpty())
            <div class="empty"><span>✉️</span>Aucun message pour le moment.</div>
        @else
            <table>
                <thead>
                <tr><th>Expéditeur</th><th>Message</th><th>Reçu</th><th></th></tr>
                </thead>
                <tbody>
                @foreach ($messages as $message)
                    <tr>
                        <td>
                            <b>{{ $message->name }}</b>
                            @unless ($message->is_read)<span class="tag tag-orange">Nouveau</span>@endunless
                            <br>
                            <small class="hint">{{ $message->phone }} {{ $message->email }}</small>
                        </td>
                        <td>{{ str($message->message)->limit(70) }}</td>
                        <td><small class="hint">{{ $message->created_at->diffForHumans() }}</small></td>
                        <td>
                            <div class="actions">
                                <a class="btn btn-line btn-sm" href="{{ route('admin.messages.show', $message) }}">Lire</a>
                                @include('admin.partials.delete-form', ['action' => route('admin.messages.destroy', $message), 'name' => 'Message de '.$message->name])
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>

    {{ $messages->links() }}

@endsection
