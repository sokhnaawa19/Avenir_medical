@extends('admin.layout')

@section('title', 'Articles du blog')

@section('content')

    <div class="page-head">
        <p>{{ $posts->total() }} article(s) — partenariats, actualités et projets.</p>
        <a class="btn btn-primary" href="{{ route('admin.posts.create') }}">➕ Écrire un article</a>
    </div>

    <form class="filters" method="GET" action="{{ route('admin.posts.index') }}">
        <input class="input" type="search" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="🔍 Rechercher un titre…">
        <button class="btn btn-line" type="submit">Rechercher</button>
    </form>

    <div class="table-wrap">
        @if ($posts->isEmpty() && $posts->currentPage() > 1)
            <div class="empty-page">
                <span>📄</span>
                <p>Cette page ne contient plus d'éléments.</p>
                <a class="btn btn-primary btn-sm" style="margin-top:12px" href="{{ route('admin.posts.index') }}">Revenir à la première page</a>
            </div>
        @elseif ($posts->isEmpty())
            <div class="empty"><span>📰</span>Aucun article pour le moment.</div>
        @else
            <table>
                <thead>
                <tr><th>Article</th><th>Catégorie</th><th>Publication</th><th>Vues</th><th></th></tr>
                </thead>
                <tbody>
                @foreach ($posts as $post)
                    <tr>
                        <td>
                            <div class="cell">
                                <span class="thumb" @if ($post->image) style="background-image:url('{{ media($post->image) }}')" @endif>
                                    @unless ($post->image)📰@endunless
                                </span>
                                <span><b>{{ $post->title }}</b><small>{{ str($post->summary())->limit(60) }}</small></span>
                            </div>
                        </td>
                        <td>@if ($post->category)<span class="tag">{{ $post->category }}</span>@endif</td>
                        <td>
                            <span class="tag {{ $post->is_published ? 'tag-green' : 'tag-orange' }}">
                                {{ $post->is_published ? 'En ligne' : 'Brouillon' }}
                            </span><br>
                            <small class="hint">{{ optional($post->published_at)->translatedFormat('d M Y') }}</small>
                        </td>
                        <td>{{ $post->views }}</td>
                        <td>
                            <div class="actions">
                                @if ($post->is_published)
                                    <a class="btn btn-line btn-sm" href="{{ route('blog.show', $post) }}" target="_blank" rel="noopener">Voir</a>
                                @endif
                                <a class="btn btn-line btn-sm" href="{{ route('admin.posts.edit', $post) }}">Modifier</a>
                                @include('admin.partials.delete-form', ['action' => route('admin.posts.destroy', $post), 'name' => $post->title])
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>

    {{ $posts->links() }}

@endsection
