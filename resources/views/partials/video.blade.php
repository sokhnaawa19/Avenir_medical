{{--
    Lecteur vidéo. Variables : $url (obligatoire), $poster, $title

    - YouTube / Vimeo : la vidéo n'est chargée qu'au clic (page rapide).
    - Fichier envoyé   : lecteur du navigateur, téléchargé seulement à la lecture.
--}}
@php
    $video = video_embed($url ?? null);
    $posterUrl = ! empty($poster) ? $poster : ($video['poster'] ?? null);
@endphp

@if ($video)
    <div class="video-wrap">
        @if ($video['type'] === 'file')
            <video class="video-el" controls preload="metadata" playsinline controlsList="nodownload"
                   @if ($posterUrl) poster="{{ $posterUrl }}" @endif>
                <source src="{{ $video['src'] }}" type="{{ $video['mime'] ?? 'video/mp4' }}">
                <p class="video-fallback">
                    {{ __('site.votre_navigateur_ne_peut_pas_lire_cette_vide') }}
                    <a href="{{ $video['src'] }}" download>{{ __('site.la_telecharger') }}</a>
                </p>
            </video>
        @else
            <button type="button" class="video-lite" data-video="{{ $video['src'] }}"
                    aria-label="Lire la vidéo{{ ! empty($title) ? ' : '.$title : '' }}">
                @if ($posterUrl)
                    <img class="video-poster" src="{{ $posterUrl }}" alt=""
                         width="1280" height="720" loading="lazy" decoding="async">
                @endif
                <span class="video-play" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="26" height="26" fill="currentColor">
                        <path d="M8 5.14v13.72a1 1 0 0 0 1.54.84l10.3-6.86a1 1 0 0 0 0-1.68L9.54 4.3A1 1 0 0 0 8 5.14z"/>
                    </svg>
                </span>
            </button>
        @endif
    </div>
@elseif (! empty($url))
    {{-- Le lien saisi n'a pas été reconnu : on prévient plutôt que d'afficher un cadre vide. --}}
    @auth
        @if (auth()->user()->isAdmin())
            <p class="video-warning">
                @include('partials.icon', ['name' => 'alert']) Ce lien vidéo n'est pas reconnu : <code>{{ $url }}</code><br>
                Utilisez un lien YouTube, Vimeo, ou un fichier .mp4 / .webm / .mov.
            </p>
        @endif
    @endauth
@endif
