@extends('layouts.public')

@section('title', 'Notre savoir-faire — '.setting('site_name'))
@section('meta_description', "Techniciens formés à l'étranger chez les fabricants, partenariats exclusifs : le savoir-faire d'AVENIR MEDICAL.")

@section('content')

    @include('partials.page-hero', [
        'title' => setting('expertise_title'),
        'text' => setting('expertise_text'),
        'crumb' => '<a href="'.lroute('references').'">'.__('site.references').'</a> › Savoir-faire',
    ])

    {{-- Les partenariats exclusifs --}}
    @if ($exclusives->isNotEmpty())
        <section>
            <div class="wrap">
                @include('partials.flash')

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

                            @if ($partner->website)
                                <a class="link" href="{{ $partner->website }}" target="_blank" rel="noopener">{{ __('site.site_du_fabricant') }}</a>
                            @endif
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Les formations des techniciens --}}
    @if ($trainings->isNotEmpty())
        <section class="training-section" @if (! $exclusives->isNotEmpty()) style="padding-top:80px" @endif>
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
                                @if ($training->year)
                                    <time>{{ $training->year }}</time>
                                @endif

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
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if ($exclusives->isEmpty() && $trainings->isEmpty())
        <section>
            <div class="wrap">
                <p class="vide"><span>@include('partials.icon', ['name' => 'graduation', 'size' => 48])</span>{{ __('site.cette_page_sera_bientot_completee') }}</p>
            </div>
        </section>
    @endif

    <section style="padding-top:0">
        <div class="wrap center">
            <a class="btn btn-primary" href="{{ lroute('contact') }}">{{ __('site.parler_a_un_technicien') }}</a>
            <a class="btn btn-line" href="{{ lroute('gallery') }}">{{ __('site.voir_la_galerie_photos') }}</a>
        </div>
    </section>

@endsection
