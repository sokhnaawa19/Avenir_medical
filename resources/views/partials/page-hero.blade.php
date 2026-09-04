{{-- Bandeau de titre des pages internes. Variables : $title, $text, $crumb --}}
<section class="page-hero">
    <div class="wrap">
        @if (! empty($crumb))
            <div class="crumb"><a href="{{ lroute('home') }}">{{ __('site.accueil') }}</a> › {!! $crumb !!}</div>
        @endif
        <h1>{{ $title }}</h1>
        @if (! empty($text))
            <p>{{ $text }}</p>
        @endif
    </div>
</section>
