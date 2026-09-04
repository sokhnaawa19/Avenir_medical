{{-- Carte d'un article de blog. Variable : $post --}}
<a class="post" href="{{ lroute('blog.show', $post) }}">
    <div class="img">
        @include('partials.cover', ['url' => media($post->image), 'alt' => $post->title, 'w' => 600, 'h' => 400])
        @if ($post->category)
            <span class="tag">{{ $post->category }}</span>
        @endif
        @if ($post->video_url)
            <span class="tag tag-video" aria-hidden="true">{{ __('site.video') }}</span>
        @endif
    </div>
    <div class="body">
        <time>{{ optional($post->published_at)->translatedFormat('d F Y') }}</time>
        <h3>{{ $post->title }}</h3>
        <p>{{ $post->summary() }}</p>
    </div>
</a>
