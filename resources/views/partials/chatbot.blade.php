{{--
    Assistant de discussion : petite bulle « Puis-je vous aider ? ».
    Entièrement paramétrable depuis l'administration (Réglages → Assistant).
    Aucun compte externe, aucune donnée envoyée ailleurs.
--}}
@php
    // Questions/réponses renseignées dans l'administration
    $chatItems = collect([1, 2, 3, 4])
        ->map(fn (int $i): array => [
            'question' => (string) setting('chat_q'.$i),
            'answer' => (string) setting('chat_a'.$i),
        ])
        ->filter(fn (array $item): bool => filled($item['question']) && filled($item['answer']))
        ->values();

    $whatsapp = setting('whatsapp') ? preg_replace('/\D/', '', (string) setting('whatsapp')) : null;
@endphp

@if (settings()->boolean('chat_enabled', true) && $chatItems->isNotEmpty())
    <div class="chat" id="chat" data-delay="{{ (int) setting('chat_delay', 4) }}">

        {{-- Bulle d'invitation --}}
        <div class="chat-teaser" id="chatTeaser" hidden>
            <button class="chat-teaser-close" type="button" id="chatTeaserClose" aria-label="{{ __('site.fermer') }}">×</button>
            <p>{{ setting('chat_welcome') }}</p>
        </div>

        {{-- Fenêtre de discussion --}}
        <div class="chat-box" id="chatBox" hidden role="dialog" aria-label="{{ __('site.assistant_avenir_medical') }}">
            <div class="chat-head">
                <div class="chat-head-info">
                    <span class="chat-avatar" aria-hidden="true">
                        <svg viewBox="0 0 40 40" width="24" height="24" aria-hidden="true">
                            <polygon points="8,7 8,33 24,33" fill="currentColor"/>
                            <polygon points="12,6 32,6 27,30" fill="currentColor" opacity=".65"/>
                        </svg>
                    </span>
                    <div>
                        <b>{{ setting('chat_title') }}</b>
                        <small>{{ setting('opening_hours') }}</small>
                    </div>
                </div>
                <button class="chat-close" type="button" id="chatClose" aria-label="{{ __('site.fermer_la_discussion') }}">×</button>
            </div>

            <div class="chat-body" id="chatBody">
                <div class="chat-msg chat-msg--bot">{{ setting('chat_welcome') }}</div>
            </div>

            <div class="chat-choices" id="chatChoices">
                @foreach ($chatItems as $item)
                    <button type="button" class="chat-choice"
                            data-answer="{{ $item['answer'] }}">{{ $item['question'] }}</button>
                @endforeach
            </div>

            <div class="chat-foot">
                @if (setting('phone_1'))
                    <a class="chat-action" href="tel:{{ preg_replace('/\s+/', '', (string) setting('phone_1')) }}">
                        {{ __('site.appeler') }}
                    </a>
                @endif
                @if ($whatsapp)
                    <a class="chat-action chat-action--wa" href="https://wa.me/{{ $whatsapp }}" target="_blank" rel="noopener">
                        WhatsApp
                    </a>
                @endif
                <a class="chat-action" href="{{ lroute('contact') }}">{{ __('site.nous_ecrire') }}</a>
            </div>
        </div>

        {{-- Bouton rond --}}
        <button class="chat-toggle" id="chatToggle" type="button" aria-label="{{ __('site.ouvrir_la_discussion') }}">
            <svg class="chat-icon-open" viewBox="0 0 24 24" width="26" height="26" fill="none"
                 stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8z"/>
            </svg>
            <span class="chat-icon-close" aria-hidden="true">×</span>
        </button>
    </div>
@endif
