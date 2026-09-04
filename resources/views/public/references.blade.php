@extends('layouts.public')

@section('title', 'Nos références — '.setting('site_name'))
@section('meta_description', 'Les établissements de santé qui nous font confiance : hôpitaux, cliniques et centres de santé équipés par AVENIR MEDICAL.')

@section('content')

    @include('partials.page-hero', [
        'title' => setting('references_title'),
        'text' => setting('references_text'),
        'crumb' => __('site.references'),
    ])

    <section>
        <div class="wrap">
            @include('partials.flash')

            @if ($stats['total'] > 0)
                <div class="ref-stats">
                    <div class="ref-stat"><b>{{ $stats['total'] }}</b><small>{{ __('site.etablissements_accompagnes') }}</small></div>
                    @if ($stats['flagship'] > 0)
                        <div class="ref-stat"><b>{{ $stats['flagship'] }}</b><small>{{ __('site.equipes_integralement') }}</small></div>
                    @endif
                    @if ($stats['cities'] > 0)
                        <div class="ref-stat"><b>{{ $stats['cities'] }}</b><small>{{ __('site.villes_couvertes') }}</small></div>
                    @endif
                </div>
            @endif

            {{-- Les réalisations phares : établissements équipés entièrement --}}
            @if ($flagships->isNotEmpty())
                <div class="sec-head" style="margin-top:56px">
                    <span class="eyebrow">{{ __('site.realisations_phares') }}</span>
                    <h2>{{ __('site.des_etablissements_equipes_de_a_a_z') }}</h2>
                </div>

                <div class="ref-flagships">
                    @foreach ($flagships as $item)
                        <article class="ref-card">
                            <div class="ref-card-visual">
                                @if ($item->image)
                                    <img src="{{ media($item->image) }}" alt="{{ $item->name }}"
                                         width="700" height="500" loading="lazy" decoding="async">
                                @else
                                    <span class="ref-placeholder">{{ $item->initials() }}</span>
                                @endif
                                <span class="ref-flag">{{ __('site.equipement_complet') }}</span>
                            </div>

                            <div class="ref-card-body">
                                <div class="ref-card-head">
                                    @if ($item->logo)
                                        <img class="ref-logo" src="{{ media($item->logo) }}" alt=""
                                             width="120" height="60" loading="lazy" decoding="async">
                                    @endif
                                    <div>
                                        <h3>{{ $item->name }}</h3>
                                        <small>{{ collect([$item->type, $item->location(), $item->year])->filter()->implode(' · ') }}</small>
                                    </div>
                                </div>

                                @if ($item->description)
                                    <p>{{ $item->description }}</p>
                                @endif

                                @if ($item->equipmentList() !== [])
                                    <ul class="ref-equipments">
                                        @foreach ($item->equipmentList() as $equipement)
                                            <li>{{ $equipement }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif

            {{-- Les autres établissements --}}
            @if ($others->isNotEmpty())
                <div class="sec-head" style="margin-top:60px">
                    <span class="eyebrow">{{ __('site.ils_nous_font_confiance') }}</span>
                    <h2>{{ __('site.les_etablissements_que_nous_accompagnons') }}</h2>
                </div>

                <div class="ref-grid">
                    @foreach ($others as $item)
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
            @endif

            @if ($flagships->isEmpty() && $others->isEmpty())
                <p class="vide"><span>@include('partials.icon', ['name' => 'hospital', 'size' => 58])</span>{{ __('site.nos_references_seront_bientot_presentees_ici') }}</p>
            @endif

            @if ($photos->isNotEmpty())
                <div class="sec-head" style="margin-top:60px">
                    <span class="eyebrow">{{ __('site.en_images') }}</span>
                    <h2>{{ __('site.nos_realisations_en_photos') }}</h2>
                </div>

                <div class="home-gallery">
                    @foreach ($photos as $photo)
                        <a class="home-gallery-item" href="{{ lroute('gallery') }}" title="{{ $photo->title }}">
                            <img src="{{ media($photo->image) }}" alt="{{ $photo->title }}"
                                 width="400" height="400" loading="lazy" decoding="async">
                            @if ($photo->title)<span>{{ $photo->title }}</span>@endif
                        </a>
                    @endforeach
                </div>
            @endif

            <div class="center mt-3" style="margin-top:50px">
                <a class="btn btn-primary" href="{{ lroute('contact') }}">{{ __('site.confiez_nous_votre_projet') }}</a>
                <a class="btn btn-line" href="{{ lroute('gallery') }}">{{ __('site.voir_la_galerie') }}</a>
            </div>
        </div>
    </section>

@endsection
