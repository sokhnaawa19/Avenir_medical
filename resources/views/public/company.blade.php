@extends('layouts.public')

@section('title', "Qui sommes-nous ? — ".setting('site_name'))

@section('content')

    @include('partials.page-hero', [
        'title' => 'Qui sommes-nous ?',
        'text' => setting('site_baseline'),
        'crumb' => "L'entreprise",
    ])

    <section>
        <div class="wrap">
            @include('partials.flash')

            <div class="pres-grid">
                <div class="collage">
                    @foreach (['about_image_1', 'about_image_2', 'about_image_3'] as $image)
                        @if (setting_image($image))
                            <img class="img" src="{{ setting_image($image) }}" alt="" width="600" height="400"
                                 loading="lazy" decoding="async">
                        @else
                            <div class="img"></div>
                        @endif
                    @endforeach
                </div>

                <div class="pres-txt">
                    <span class="eyebrow">{{ setting('about_eyebrow') }}</span>
                    <h2>{{ setting('about_title') }}</h2>
                    <p>{{ setting('about_text') }}</p>
                    <p>{{ setting('about_text_2') }}</p>

                    <div class="pres-stats">
                        @foreach ([1, 2, 3] as $i)
                            @if (setting('stat_'.$i.'_value'))
                                <div class="ps">
                                    <b>{{ setting('stat_'.$i.'_value') }}</b>
                                    <small>{{ setting('stat_'.$i.'_label') }}</small>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>


    {{-- Notre histoire --}}
    @if ($milestones->isNotEmpty())
        <section class="history-section">
            <div class="wrap">
                <div class="sec-head center">
                    <span class="eyebrow">{{ __('site.notre_histoire') }}</span>
                    <h2>{{ setting('history_title') }}</h2>
                    @if (setting('history_text'))
                        <p>{{ setting('history_text') }}</p>
                    @endif
                </div>

                <div class="timeline">
                    @foreach ($milestones as $milestone)
                        <article class="timeline-item">
                            <div class="timeline-marker">
                                <span class="timeline-year">{{ $milestone->year }}</span>
                            </div>

                            <div class="timeline-content">
                                @if ($milestone->image)
                                    <img class="timeline-img" src="{{ media($milestone->image) }}"
                                         alt="{{ $milestone->title }}" width="480" height="300"
                                         loading="lazy" decoding="async">
                                @endif

                                <h3>{{ $milestone->title }}</h3>
                                @if ($milestone->description)
                                    <p>{{ $milestone->description }}</p>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if ($values->isNotEmpty())
        <section style="padding-top:0">
            <div class="wrap">
                <div class="sec-head center">
                    <span class="eyebrow">{{ __('site.nos_valeurs') }}</span>
                    <h2>{{ setting('values_title') }}</h2>
                </div>

                <div class="grid-3">
                    @foreach ($values as $value)
                        <div class="card">
                            <h3><span class="svc-icon">@include('partials.icon-from', ['emoji' => $value->icon, 'size' => 42])</span>{{ $value->title }}</h3>
                            <p>{{ $value->description }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Le groupe : la maison mère et ses entreprises --}}
    @if ($subsidiaries->isNotEmpty())
        <section id="groupe" style="padding-top:0">
            <div class="wrap">
                <div class="sec-head center">
                    <span class="eyebrow">{{ __('site.le_groupe') }}</span>
                    <h2>{{ setting('group_title') }}</h2>
                    <p>{{ setting('group_text') }}</p>
                </div>

                <div class="group-parent">
                    <span class="group-parent-badge">{{ __('site.maison_mere') }}</span>
                    <h2 translate="no">{{ setting('site_name') }}</h2>
                    <p>{{ setting('group_parent_text') }}</p>
                </div>

                <div class="group-branches" aria-hidden="true"></div>

                <div class="group-grid">
                    @foreach ($subsidiaries as $entreprise)
                        <article class="group-card" style="--marque:{{ $entreprise->color ?: 'var(--teal)' }}">
                            @if ($entreprise->image)
                                <div class="group-card-visual">
                                    <img src="{{ media($entreprise->image) }}" alt="{{ $entreprise->name }}"
                                         width="600" height="360" loading="lazy" decoding="async">
                                </div>
                            @endif

                            <div class="group-card-body">
                                <div class="group-card-logo">
                                    @if ($entreprise->logo)
                                        <img src="{{ media($entreprise->logo) }}" alt="{{ $entreprise->name }}"
                                             width="150" height="70" loading="lazy" decoding="async">
                                    @else
                                        <span class="group-initials">{{ $entreprise->initials() }}</span>
                                    @endif
                                </div>

                                <h3 translate="no">{{ $entreprise->name }}</h3>
                                @if ($entreprise->tagline)
                                    <p class="group-tagline">{{ $entreprise->tagline }}</p>
                                @endif

                                @if ($entreprise->activity || $entreprise->founded_year)
                                    <div class="group-meta">
                                        @if ($entreprise->activity)<span class="badge-soft">{{ $entreprise->activity }}</span>@endif
                                        @if ($entreprise->founded_year)<span class="badge-soft">Depuis {{ $entreprise->founded_year }}</span>@endif
                                    </div>
                                @endif

                                @if ($entreprise->description)
                                    <p>{{ $entreprise->description }}</p>
                                @endif

                                @if ($entreprise->website)
                                    <a class="link" href="{{ $entreprise->website }}" target="_blank" rel="noopener">
                                        Découvrir {{ $entreprise->name }} →
                                    </a>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif


    <section style="padding-top:0">
        <div class="wrap center">
            <a class="btn btn-primary" href="{{ lroute('contact') }}">{{ __('site.discuter_de_votre_projet') }}</a>
        </div>
    </section>

@endsection
