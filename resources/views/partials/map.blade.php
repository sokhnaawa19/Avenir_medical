{{--
    La carte Google en bas de page, avec les coordonnées par-dessus.
    Le code d'intégration se colle dans Réglages → Contact → Carte Google Maps.
--}}
@if (setting('map_embed'))
    <section class="map-section" aria-label="{{ __('site.nous_trouver') }}">
        <div class="map-wrap">
            {!! setting('map_embed') !!}

            <div class="map-card">
                <span class="eyebrow">{{ __('site.nous_trouver') }}</span>
                <h3 translate="no">{{ setting('site_name') }}</h3>

                @if (setting('address'))
                    <p translate="no">{{ setting('address') }}</p>
                @endif

                @if (setting('phone_1'))
                    <p>
                        <a href="tel:{{ preg_replace('/\s+/', '', (string) setting('phone_1')) }}" translate="no">
                            {{ setting('phone_1') }}
                        </a>
                    </p>
                @endif

                @if (setting('email'))
                    <p><a href="mailto:{{ setting('email') }}" translate="no">{{ setting('email') }}</a></p>
                @endif

                @if (setting('opening_hours'))
                    <p>{{ setting('opening_hours') }}</p>
                @endif

                <a class="btn btn-primary btn-sm" href="{{ lroute('contact') }}">{{ __('site.talk_expert') }}</a>
            </div>
        </div>
    </section>
@endif
