@extends('layouts.public')

@section('title', 'Nos partenaires — '.setting('site_name'))

@section('content')

    @php use App\Support\LineList; @endphp

    @include('partials.page-hero', [
        'title' => __('site.nos_partenaires'),
        'text' => setting('partners_text'),
        'crumb' => __('site.partenaires'),
    ])

    <section>
        <div class="wrap">
            @include('partials.flash')

            @if ($partners->isEmpty())
                <p class="vide"><span>@include('partials.icon', ['name' => 'handshake', 'size' => 58])</span>{{ __('site.les_partenaires_seront_bientot_presentes_ici') }}</p>
            @else
                <div class="partners-grid partners-grid--large">
                    @foreach ($partners as $partner)
                        <div class="partner-detail">
                            <div class="partner-logo">
                                @if ($partner->logo)
                                    <img src="{{ media($partner->logo) }}" alt="{{ $partner->name }}"
                                         width="200" height="100" loading="lazy" decoding="async">
                                @else
                                    <span class="partner-initials">{{ $partner->initials() }}</span>
                                @endif
                            </div>

                            <h3 translate="no">{{ $partner->name }}</h3>

                            @if ($partner->country)
                                <small class="partner-country">{{ $partner->country }}</small>
                            @endif

                            @if ($partner->description)
                                <p>{{ $partner->description }}</p>
                            @endif

                            @if ($partner->domains->isNotEmpty())
                                @php
                                    $ranges = $partner->domains
                                        ->flatMap(fn ($domain) => LineList::toPairs($domain->pivot->ranges))
                                        ->pluck('title');
                                @endphp

                                <span class="partner-scope-label">{{ __('site.domaines_couverts') }}</span>
                                <ul class="partner-domains">
                                    @foreach ($partner->domains as $domain)
                                        <li>
                                            <a href="{{ lroute('domains') }}#{{ $domain->slug }}">
                                                <span aria-hidden="true">@include('partials.icon-from', ['emoji' => $domain->displayIcon()])</span> {{ $domain->title }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>

                                @if ($ranges->isNotEmpty())
                                    <p class="partner-ranges">{{ $ranges->implode(' · ') }}</p>
                                @endif
                            @endif

                            @if ($partner->website)
                                <a class="link" href="{{ $partner->website }}" target="_blank" rel="noopener">
                                    {{ __('site.visiter_le_site') }}
                                </a>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

@endsection
