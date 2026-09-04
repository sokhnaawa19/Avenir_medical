@extends('layouts.public')

@section('title', $post->title.' — '.setting('site_name'))
@section('meta_description', $post->summary(150))

@section('content')

    @include('partials.page-hero', [
        'title' => $post->title,
        'text' => __('site.publie_le').optional($post->published_at)->translatedFormat('d F Y').($post->category ? ' · '.$post->category : ''),
        'crumb' => '<a href="'.lroute('blog.index').'">Blog</a> › Article',
    ])

    <section>
        <div class="wrap">
            @if ($post->video_url)
                <div style="max-width:900px;margin:0 auto 34px">
                    @include('partials.video', [
                        'url' => $post->video_url,
                        'poster' => media($post->image),
                        'title' => $post->title,
                    ])
                </div>
            @elseif ($post->image)
                <div class="article-img">
                    @include('partials.cover', [
                        'url' => media($post->image),
                        'alt' => $post->title,
                        'w' => 1200, 'h' => 630, 'priority' => true,
                    ])
                </div>
            @endif

            <div class="article-corps">
                @if ($post->excerpt)
                    <p><b>{{ $post->excerpt }}</b></p>
                @endif

                {!! nl2br(e((string) $post->content)) !!}

                <p class="text-muted mt-3" style="font-size:.88rem">
                    {{ $post->views }} lecture(s)
                    @if ($post->author)
                        · publié par {{ $post->author->name }}
                    @endif
                </p>

                <a class="btn btn-primary mt-2" href="{{ lroute('blog.index') }}">{{ __('site.retour_au_blog') }}</a>
            </div>
        </div>
    </section>

    @if ($related->isNotEmpty())
        <section style="padding-top:0">
            <div class="wrap">
                <div class="sec-head"><span class="eyebrow">{{ __('site.a_lire_aussi') }}</span><h2>{{ __('site.autres_actualites') }}</h2></div>
                <div class="blog-grid">
                    @foreach ($related as $item)
                        @include('partials.post-card', ['post' => $item])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

@endsection
