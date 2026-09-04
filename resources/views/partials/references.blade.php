{{-- Les établissements qui nous font confiance. Variable : $establishments --}}
@if ($establishments->isNotEmpty())
    <section class="reveal band-white">
        <div class="wrap">
            <div class="sec-head center">
                <span class="eyebrow">{{ __('site.ils_nous_font_confiance') }}</span>
                <h2>{{ setting('references_home_title') }}</h2>
                <p>{{ setting('references_home_text') }}</p>
            </div>

            <div class="ref-grid">
                @foreach ($establishments as $item)
                    <div class="ref-mini">
                        <div class="ref-mini-logo">
                            @if ($item->logo)
                                <img src="{{ media($item->logo) }}" alt="{{ $item->name }}"
                                     width="140" height="70" loading="lazy" decoding="async">
                            @else
                                <span class="ref-placeholder">{{ $item->initials() }}</span>
                            @endif
                        </div>
                        <b>{{ $item->name }}</b>
                        <small>{{ collect([$item->type, $item->city])->filter()->implode(' · ') }}</small>
                    </div>
                @endforeach
            </div>

            <div class="center mt-3">
                <a class="btn btn-primary" href="{{ lroute('references') }}">{{ __('site.voir_toutes_nos_references') }}</a>
            </div>
        </div>
    </section>
@endif
