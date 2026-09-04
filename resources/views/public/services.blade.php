@extends('layouts.public')

@section('title', 'Services & expertise — '.setting('site_name'))
@section('meta_description', "Service après-vente, conseil, techniciens formés chez les fabricants et partenariats exclusifs : l'expertise d'AVENIR MEDICAL.")

@section('content')

    @include('partials.page-hero', [
        'title' => setting('services_page_title'),
        'text' => setting('services_page_text'),
        'crumb' => __('site.services_expertise'),
    ])

    {{-- 1. Ce que nous faisons --}}
    <section>
        <div class="wrap">
            @include('partials.flash')

            <div class="sec-head center">
                <span class="eyebrow">{{ setting('services_eyebrow') }}</span>
                <h2>{{ setting('services_title') }}</h2>
                <p>{{ setting('services_text') }}</p>
            </div>

            <div class="services-grid">
                @foreach ($services as $service)
                    <article class="service-card">
                        @if ($service->image)
                            <div class="service-card-media">
                                <img src="{{ media($service->image) }}" alt="{{ $service->title }}"
                                     width="700" height="500" loading="lazy" decoding="async">
                            </div>
                        @endif

                        <div class="service-card-body">
                            <h3 class="service-card-title">
                                <span class="service-card-icon">
                                    @include('partials.icon-from', ['emoji' => $service->icon, 'size' => 26])
                                </span>
                                {{ $service->title }}
                            </h3>

                            <p class="service-card-text">{{ $service->description }}</p>

                            {{-- Les réalisations en photos --}}
                            @if ($service->photos->isNotEmpty())
                                <div class="service-photos">
                                    @foreach ($service->photos->take(4) as $photo)
                                        <figure class="photo-item service-photo"
                                                data-full="{{ media($photo->image) }}"
                                                data-title="{{ $service->title }}"
                                                data-caption="{{ $photo->caption }}"
                                                tabindex="0" role="button" aria-label="{{ __('site.agrandir_la_photo') }}">
                                            <img src="{{ media($photo->image) }}" alt=""
                                                 width="200" height="200" loading="lazy" decoding="async">
                                        </figure>
                                    @endforeach

                                    @if ($service->photos->count() > 4)
                                        <span class="training-photos-more">+{{ $service->photos->count() - 4 }}</span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- 2. Ce qui fait la différence : nos partenariats exclusifs --}}
    @if ($exclusives->isNotEmpty())
        <section class="exclusive-band" id="exclusivites">
            <div class="wrap">
                <div class="sec-head center">
                    <span class="eyebrow">{{ __('site.representation_exclusive') }}</span>
                    <h2>{{ setting('exclusive_title') }}</h2>
                    <p>{{ setting('exclusive_text') }}</p>
                </div>

                <div class="exclusive-grid">
                    @foreach ($exclusives as $partner)
                        <article class="exclusive-card">
                            <span class="exclusive-ribbon">@include('partials.icon', ['name' => 'star', 'size' => 22]) {{ $partner->exclusivityLabel() }}</span>

                            <div class="exclusive-logo">
                                @if ($partner->logo)
                                    <img src="{{ media($partner->logo) }}" alt="{{ $partner->name }}"
                                         width="180" height="90" loading="lazy" decoding="async">
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
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- 3. Des techniciens formés chez les fabricants --}}
    @if ($trainings->isNotEmpty())
        <section class="training-section" id="formations">
            <div class="wrap">
                <div class="sec-head center">
                    <span class="eyebrow">{{ __('site.formation_continue') }}</span>
                    <h2>{{ setting('training_title') }}</h2>
                    <p>{{ setting('training_text') }}</p>
                </div>

                <div class="training-grid">
                    @foreach ($trainings as $training)
                        <article class="training-card">
                            <div class="training-visual">
                                @if ($training->image)
                                    <img src="{{ media($training->image) }}" alt="{{ $training->title }}"
                                         width="600" height="400" loading="lazy" decoding="async">
                                @else
                                    <span class="training-icon">@include('partials.icon', ['name' => 'graduation', 'size' => 48])</span>
                                @endif
                                @if ($training->country)
                                    <span class="training-country">@include('partials.icon', ['name' => 'pin']) {{ $training->country }}</span>
                                @endif
                            </div>

                            <div class="training-body">
                                @if ($training->year)<time>{{ $training->year }}</time>@endif
                                <h3>{{ $training->title }}</h3>
                                @if ($training->organism)
                                    <p class="training-organism">Chez {{ $training->organism }}</p>
                                @endif
                                @if ($training->description)
                                    <p>{{ $training->description }}</p>
                                @endif
                                @if ($training->participants)
                                    <span class="training-badge">
                                        @include('partials.icon', ['name' => 'wrench']) {{ $training->participants }}
                                        {{ $training->participants > 1 ? __('site.techniciens_formes') : __('site.technicien_forme') }}
                                    </span>
                                @endif

                                @if ($training->photos->isNotEmpty())
                                    <div class="training-photos">
                                        @foreach ($training->photos->take(4) as $photo)
                                            <figure class="photo-item training-photo"
                                                    data-full="{{ media($photo->image) }}"
                                                    data-title="{{ $training->title }}"
                                                    data-caption="{{ $photo->caption }}"
                                                    tabindex="0" role="button"
                                                    aria-label="{{ __('site.agrandir_la_photo') }}">
                                                <img src="{{ media($photo->image) }}" alt=""
                                                     width="200" height="200" loading="lazy" decoding="async">
                                            </figure>
                                        @endforeach

                                        @if ($training->photos->count() > 4)
                                            <span class="training-photos-more">+{{ $training->photos->count() - 4 }}</span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- 4. Tous nos partenaires --}}
    <span id="partenaires"></span>
    @include('partials.partners', [
        'partners' => $partners,
        'eyebrow' => 'Nos partenaires',
        'title' => setting('partners_title'),
        'text' => setting('partners_text'),
    ])

    <section style="padding-top:0">
        <div class="wrap center">
            <a class="btn btn-primary" href="{{ lroute('contact') }}">{{ __('site.demander_un_devis_gratuit') }}</a>
            <a class="btn btn-line" href="{{ lroute('references') }}">{{ __('site.voir_nos_references') }}</a>
        </div>
    </section>

@endsection
