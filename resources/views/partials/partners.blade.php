{{--
    Bande des logos partenaires.
    Variables : $partners, $title, $text, $eyebrow, $dark (fond sombre)
--}}
@if ($partners->isNotEmpty())
    <section class="partners-section bg-section {{ ! empty($dark) ? 'is-dark' : '' }} {{ setting_image('partners_bg') ? 'has-bg' : '' }}">
        @if (setting_image('partners_bg'))
            <img class="bg-section-img" src="{{ setting_image('partners_bg') }}" alt=""
                 width="1600" height="700" loading="lazy" decoding="async">
            <div class="bg-section-veil"></div>
        @endif

        <div class="wrap">
            <div class="sec-head center">
                <span class="eyebrow">{{ $eyebrow ?? 'Ils nous font confiance' }}</span>
                <h2>{{ $title ?? setting('partners_title') }}</h2>
                @if (! empty($text))
                    <p>{{ $text }}</p>
                @endif
            </div>

            <div class="partners-grid">
                @foreach ($partners as $partner)
                    @php $tag = $partner->website ? 'a' : 'div'; @endphp

                    <{{ $tag }} class="partner-card"
                        @if ($partner->website) href="{{ $partner->website }}" target="_blank" rel="noopener" @endif
                        title="{{ $partner->name }}">

                        <div class="partner-logo">
                            @if ($partner->logo)
                                <img src="{{ media($partner->logo) }}" alt="{{ $partner->name }}"
                                     width="160" height="80" loading="lazy" decoding="async">
                            @else
                                <span class="partner-initials">{{ $partner->initials() }}</span>
                            @endif
                        </div>

                        <span class="partner-name" translate="no">{{ $partner->name }}</span>

                        @if ($partner->country)
                            <small class="partner-country">{{ $partner->country }}</small>
                        @endif
                    </{{ $tag }}>
                @endforeach
            </div>
        </div>
    </section>
@endif
