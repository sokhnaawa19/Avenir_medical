<section class="contact" id="contact" style="margin-top:90px">
    <div class="wrap">
        <h2>{{ setting('cta_title') }}</h2>
        <p>{{ setting('cta_text') }}</p>

        <div class="c-row">
            @foreach (['phone_1', 'phone_2', 'phone_3'] as $phone)
                @if (setting($phone))
                    <a href="tel:{{ preg_replace('/\s+/', '', setting($phone)) }}">
                        <svg width="14" height="14" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true">
                            <path d="M3.654 1.328a.678.678 0 0 0-1.015-.063L1.605 2.3c-.483.484-.661 1.169-.45 1.77a17.6 17.6 0 0 0 4.168 6.608 17.6 17.6 0 0 0 6.608 4.168c.601.211 1.286.033 1.77-.45l1.034-1.034a.678.678 0 0 0-.063-1.015l-2.307-1.794a.68.68 0 0 0-.58-.122l-2.19.547a1.75 1.75 0 0 1-1.657-.459L5.482 8.062a1.75 1.75 0 0 1-.46-1.657l.548-2.19a.68.68 0 0 0-.122-.58z"/>
                        </svg>
                        <span>{{ setting($phone) }}</span>
                    </a>
                @endif
            @endforeach

            @if (setting('email'))
                <a href="mailto:{{ setting('email') }}">
                    <svg width="14" height="14" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true">
                        <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414zM0 4.697v7.104l5.803-3.558zM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586zm3.436-.586L16 11.801V4.697z"/>
                    </svg>
                    <span>{{ setting('email') }}</span>
                </a>
            @endif
        </div>

        @if (setting('facebook') || setting('instagram') || setting('linkedin') || setting('youtube'))
            <div class="social-links">
                @if (setting('facebook'))
                    <a href="{{ setting('facebook') }}" target="_blank" rel="noopener" aria-label="Facebook" title="Facebook">f</a>
                @endif
                @if (setting('instagram'))
                    <a href="{{ setting('instagram') }}" target="_blank" rel="noopener" aria-label="Instagram" title="Instagram">
                        <svg width="14" height="14" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true">
                            <path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.9 3.9 0 0 0-1.417.923A3.9 3.9 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.9 3.9 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.9 3.9 0 0 0-.923-1.417A3.9 3.9 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599s.453.546.598.919c.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.5 2.5 0 0 1-.599.919c-.28.28-.546.453-.919.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.5 2.5 0 0 1-.92-.598 2.5 2.5 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233s.008-2.388.046-3.231c.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92s.546-.453.92-.598c.281-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92m-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217m0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334"/>
                        </svg>
                    </a>
                @endif
                @if (setting('linkedin'))
                    <a href="{{ setting('linkedin') }}" target="_blank" rel="noopener" aria-label="LinkedIn" title="LinkedIn">Li</a>
                @endif
                @if (setting('youtube'))
                    <a href="{{ setting('youtube') }}" target="_blank" rel="noopener" aria-label="YouTube" title="YouTube">
                        <svg width="14" height="14" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true">
                            <path d="M8.051 1.999h.089c.822.003 4.987.033 6.11.335a2.01 2.01 0 0 1 1.415 1.42c.101.38.172.883.22 1.402l.01.104.022.26.008.104c.065.914.073 1.77.074 1.957v.075c-.001.194-.01 1.108-.082 2.06l-.008.105-.009.104c-.05.572-.124 1.14-.235 1.558a2.01 2.01 0 0 1-1.415 1.42c-1.16.312-5.569.334-6.18.335h-.142c-.309 0-1.587-.006-2.927-.052l-.17-.006-.087-.004-.171-.007-.171-.007c-1.11-.049-2.167-.128-2.654-.26a2.01 2.01 0 0 1-1.415-1.419c-.111-.417-.185-.986-.235-1.558L.09 9.82l-.008-.104A31 31 0 0 1 0 7.68v-.123c.002-.215.01-.958.064-1.778l.007-.103.003-.052.008-.104.022-.26.01-.104c.048-.519.119-1.023.22-1.402a2.01 2.01 0 0 1 1.415-1.42c.487-.13 1.544-.21 2.654-.26l.17-.007.172-.006.086-.003.171-.007A100 100 0 0 1 7.858 2zM6.4 5.209v4.818l4.157-2.408z"/>
                        </svg>
                    </a>
                @endif
            </div>
        @endif
    </div>
</section>

<footer>
    <div class="wrap">
        <p style="max-width:60ch;margin:0 auto 14px">{{ setting('footer_text') }}</p>
        <p style="margin-bottom:10px">
            <a class="link" href="{{ lroute('gallery') }}">{{ __('site.galerie_photos') }}</a>
            {{ __('site.nbsp_nbsp') }}
            <a class="link" href="{{ lroute('company') }}#groupe">{{ __('site.le_groupe') }}</a>
            {{ __('site.nbsp_nbsp') }}
            <a class="link" href="{{ lroute('legal') }}">{{ __('site.mentions_legales') }}</a>
            {{ __('site.nbsp_nbsp') }}
            <a class="link" href="{{ lroute('terms') }}">{{ __('site.conditions_generales_de_vente') }}</a>
        </p>
        <p>{{ str_replace(':year', now()->format('Y'), (string) setting('copyright')) }}</p>
    </div>
</footer>
