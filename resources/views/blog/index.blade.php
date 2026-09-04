@extends('layouts.public')

@section('title', 'Blog & actualités — '.setting('site_name'))

@section('content')

    @include('partials.page-hero', [
        'title' => __('site.blog_actualites'),
        'text' => setting('blog_text'),
        'crumb' => __('site.menu.blog'),
    ])

    <section>
        <div class="wrap">
            @include('partials.flash')

            @if ($categories->isNotEmpty())
                <div class="shop-tools">
                    <a class="pill @if (! $currentCategory) on @endif" href="{{ lroute('blog.index') }}">{{ __('site.tout') }}</a>
                    @foreach ($categories as $category)
                        <a class="pill @if ($currentCategory === $category['valeur']) on @endif"
                           href="{{ lroute('blog.index', ['categorie' => $category['valeur']]) }}">{{ $category['libelle'] }}</a>
                    @endforeach
                </div>
            @endif

            @if ($posts->isEmpty())
                <p class="vide"><span>@include('partials.icon', ['name' => 'news', 'size' => 58])</span>{{ __('site.aucun_article_pour_le_moment_revenez_bientot') }}</p>
            @else
                <div class="blog-grid">
                    @foreach ($posts as $post)
                        @include('partials.post-card', ['post' => $post])
                    @endforeach
                </div>

                {{ $posts->links() }}
            @endif
        </div>
    </section>

@endsection
