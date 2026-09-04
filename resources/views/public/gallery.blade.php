@extends('layouts.public')

@section('title', 'Galerie photos — '.setting('site_name'))

@section('content')

    @include('partials.page-hero', [
        'title' => setting('gallery_page_title'),
        'text' => setting('gallery_page_text'),
        'crumb' => __('site.galerie'),
    ])

    <section>
        <div class="wrap">
            @include('partials.flash')

            @if ($albums->isNotEmpty())
                <div class="shop-tools">
                    <a class="pill @if (! $currentAlbum) on @endif" href="{{ lroute('gallery') }}">{{ __('site.tout') }}</a>
                    @foreach ($albums as $album)
                        <a class="pill @if ($currentAlbum === $album) on @endif"
                           href="{{ lroute('gallery', ['album' => $album]) }}">{{ $album }}</a>
                    @endforeach
                </div>
            @endif

            @if ($photos->isEmpty())
                <p class="vide"><span>@include('partials.icon', ['name' => 'camera', 'size' => 58])</span>{{ __('site.les_photos_seront_bientot_en_ligne') }}</p>
            @else
                <div class="photo-grid">
                    @foreach ($photos as $photo)
                        <figure class="photo-item"
                                data-full="{{ media($photo->image) }}"
                                data-title="{{ $photo->title }}"
                                data-caption="{{ $photo->caption }}"
                                tabindex="0" role="button"
                                aria-label="Agrandir : {{ $photo->title ?: 'photo' }}">
                            <img src="{{ media($photo->image) }}" alt="{{ $photo->title }}"
                                 width="500" height="500" loading="lazy" decoding="async">

                            @if ($photo->title || $photo->album)
                                <figcaption>
                                    @if ($photo->title)<b>{{ $photo->title }}</b>@endif
                                    @if ($photo->taken_at)<small>{{ $photo->taken_at }}</small>@endif
                                </figcaption>
                            @endif
                        </figure>
                    @endforeach
                </div>

                {{ $photos->links() }}
            @endif
        </div>
    </section>

@endsection
