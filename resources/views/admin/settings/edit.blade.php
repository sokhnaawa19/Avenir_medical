@extends('admin.layout')

@section('title', 'Réglages du site')

@section('content')

    <div class="page-head">
        <p>Tout ce qui s'affiche sur le site se modifie ici, sans toucher au code.</p>
        <a class="btn btn-line" href="{{ route('home') }}" target="_blank" rel="noopener">🌍 Voir le site</a>
    </div>

    <div class="tabs-settings">
        @foreach ($groups as $key => $item)
            <a class="@if ($key === $group) on @endif" href="{{ route('admin.settings.edit', $key) }}">
                {{ $item['icon'] ?? '' }} {{ $item['label'] }}
            </a>
        @endforeach
    </div>

    <form method="POST" action="{{ route('admin.settings.update', $group) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="card" style="max-width:860px">
            <h2>{{ $definition['icon'] ?? '' }} {{ $definition['label'] }}</h2>

            @if (! empty($definition['description']))
                <p class="hint" style="margin-bottom:22px">{{ $definition['description'] }}</p>
            @endif

            @foreach ($definition['fields'] as $key => $field)
                @include('admin.partials.setting-field', ['key' => $key, 'field' => $field])
            @endforeach

            <div class="form-actions">
                <button class="btn btn-primary" type="submit">💾 Enregistrer les réglages</button>
            </div>
        </div>
    </form>

@endsection
