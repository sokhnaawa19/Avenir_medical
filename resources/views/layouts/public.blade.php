<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', setting('meta_title'))</title>
    <meta name="description" content="@yield('meta_description', setting('meta_description'))">

    <meta property="og:title" content="@yield('title', setting('meta_title'))">
    <meta property="og:description" content="@yield('meta_description', setting('meta_description'))">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    @if (setting_image('og_image'))
        <meta property="og:image" content="{{ setting_image('og_image') }}">
    @endif

    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">

    {{-- Polices : chargees sans bloquer l'affichage du texte --}}
    @php $fontsUrl = 'https://fonts.googleapis.com/css2?family=Sora:wght@600;700;800&family=Inter:wght@400;500;600&display=swap'; @endphp
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" as="style" href="{{ $fontsUrl }}">
    {{-- Indique à Google que la page existe dans les deux langues --}}
@foreach (config('app.locales') as $code)
    <link rel="alternate" hreflang="{{ $code }}" href="{{ locale_url($code) }}">
@endforeach
<link rel="alternate" hreflang="x-default" href="{{ locale_url(config('app.fallback_locale')) }}">

    <link rel="stylesheet" href="{{ $fontsUrl }}" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="{{ $fontsUrl }}"></noscript>

    <link rel="stylesheet" href="{{ asset_v('assets/css/style.css') }}">

    {{-- La grande image d'accueil est chargee en priorite (score LCP) --}}
    @if (request()->routeIs('home') && setting_image('hero_image'))
        <link rel="preload" as="image" href="{{ setting_image('hero_image') }}" fetchpriority="high">
    @endif


    @stack('head')
</head>
<body>

@include('partials.header')

<main>
    @yield('content')
</main>

@include('partials.footer')

@include('partials.chatbot')

@php $whatsapps = whatsapp_lines(); @endphp

@if (count($whatsapps) === 1)
    {{-- Un seul numéro : lien direct --}}
    <a class="whatsapp-float" href="{{ $whatsapps[0]['link'] }}"
       target="_blank" rel="noopener" aria-label="Écrivez-nous sur WhatsApp" title="Écrivez-nous sur WhatsApp">
        <svg width="28" height="28" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true">
            <path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232"/>
        </svg>
    </a>
@elseif (count($whatsapps) > 1)
    {{-- Plusieurs services : le visiteur choisit son interlocuteur --}}
    <div class="whatsapp-zone" id="waZone">
        <div class="whatsapp-menu" id="waMenu" hidden>
            <p class="whatsapp-menu-title">{{ __('site.whatsapp_which') }}</p>

            @foreach ($whatsapps as $ligne)
                <a class="whatsapp-choice" href="{{ $ligne['link'] }}" target="_blank" rel="noopener">
                    <b>{{ $ligne['label'] }}</b>
                    <small>{{ $ligne['number'] }}</small>
                </a>
            @endforeach
        </div>

        <button class="whatsapp-float" type="button" id="waToggle"
                aria-label="Écrivez-nous sur WhatsApp" title="Écrivez-nous sur WhatsApp">
            <svg width="28" height="28" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true">
            <path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232"/>
        </svg>
        </button>
    </div>
@endif

<script src="{{ asset_v('assets/js/script.js') }}" defer></script>
{!! setting('analytics_code') !!}
</body>
</html>
