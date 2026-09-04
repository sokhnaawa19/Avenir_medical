@extends('layouts.public')

@section('title', "Qui sommes-nous ? — ".setting('site_name'))

@section('content')

    @include('partials.page-hero', [
        'title' => __('site.qui_sommes_nous'),
        'text' => setting('site_baseline'),
        'crumb' => __('site.l_entreprise'),
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
    {{-- Notre développement --}}
    @if ($agencies->isNotEmpty())
        @include('partials.agencies', ['agencies' => $agencies])
    @endif


    <section style="padding-top:0">
        <div class="wrap center">
            <a class="btn btn-primary" href="{{ lroute('contact') }}">{{ __('site.discuter_de_votre_projet') }}</a>
        </div>
    </section>

@endsection
