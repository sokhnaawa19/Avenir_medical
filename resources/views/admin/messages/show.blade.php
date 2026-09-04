@extends('admin.layout')

@section('title', 'Message de '.$message->name)

@section('content')

    <div class="page-head">
        <p>Reçu le {{ $message->created_at->translatedFormat('d F Y à H:i') }}</p>
        <a class="btn btn-line" href="{{ route('admin.messages.index') }}">← Retour aux messages</a>
    </div>

    <div class="card" style="max-width:760px">
        <h2>{{ $message->subject ?: 'Message depuis le site' }}</h2>

        <p class="hint">
            <b>{{ $message->name }}</b><br>
            @if ($message->phone)<a href="tel:{{ $message->phone }}">{{ $message->phone }}</a><br>@endif
            @if ($message->email)<a href="mailto:{{ $message->email }}">{{ $message->email }}</a>@endif
        </p>

        <p style="margin-top:18px;white-space:pre-line">{{ $message->message }}</p>

        <div class="form-actions" style="margin-top:24px">
            @if ($message->email)
                <a class="btn btn-primary" href="mailto:{{ $message->email }}?subject=Re: {{ $message->subject ?: 'Votre message' }}">
                    ✉️ Répondre par email
                </a>
            @endif

            <form method="POST" action="{{ route('admin.messages.update', $message) }}">
                @csrf
                @method('PATCH')
                <button class="btn btn-line" type="submit">
                    Marquer comme {{ $message->is_read ? 'non lu' : 'lu' }}
                </button>
            </form>

            @include('admin.partials.delete-form', ['action' => route('admin.messages.destroy', $message), 'name' => 'Message de '.$message->name])
        </div>
    </div>

@endsection
