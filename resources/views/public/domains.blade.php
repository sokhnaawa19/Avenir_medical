@extends('layouts.public')

@section('title', "Nos domaines d'intervention — ".setting('site_name'))
@section('meta_description', "Les ".$stats['domains']." domaines d'intervention d'".setting('site_name')." : équipements médicaux, installation, formation et maintenance partout au Sénégal.")

@section('content')

    @php use App\Support\LineList; @endphp

    @include('partials.page-hero', [
        'title' => __('site.nos_domaines_d_intervention'),
        'text' => setting('domains_text'),
        'crumb' => __('site.nos_domaines'),
    ])

    {{-- Chiffres clés et accès direct à chaque carte --}}
    @if ($domains->isNotEmpty())
        <section class="dom-overview">
            <div class="wrap">
                <div class="dom-stats">
                    <div class="dom-stat">
                        <b>{{ $stats['domains'] }}</b>
                        <small>{{ __('site.domaines_d_intervention') }}</small>
                    </div>
                    @if ($stats['equipments'] > 0)
                        <div class="dom-stat">
                            <b>{{ $stats['equipments'] }}+</b>
                            <small>{{ __('site.equipements_references') }}</small>
                        </div>
                    @endif
                    @if ($stats['brands'] > 0)
                        <div class="dom-stat">
                            <b>{{ $stats['brands'] }}</b>
                            <small>{{ $stats['brands'] > 1 ? 'marques partenaires' : 'marque partenaire' }}</small>
                        </div>
                    @endif
                    <div class="dom-stat">
                        <b>SN</b>
                        <small>{{ __('site.livraison_et_maintenance_dans_tout_le_senega') }}</small>
                    </div>
                </div>

                {{-- Le visiteur saute directement à la carte qui l'intéresse --}}
                <nav class="dom-chips" aria-label="{{ __('site.acces_direct_aux_domaines') }}">
                    @foreach ($domains as $domain)
                        <a class="dom-chip" href="#{{ $domain->slug }}">{{ $domain->title }}
                        </a>
                    @endforeach
                </nav>
            </div>
        </section>
    @endif

    <section style="padding-top:10px">
        <div class="wrap">
            @include('partials.flash')

            @if ($domains->isEmpty())
                <p class="vide"><span>@include('partials.icon', ['name' => 'box', 'size' => 58])</span>{{ __('site.les_domaines_seront_bientot_disponibles') }}</p>
            @else
                <div class="domain-list">
                    @foreach ($domains as $domain)
                        @php
                            $equipments = $domain->equipmentList();
                            $brands = $domain->brandList();
                        @endphp

                        <article class="domain-card" id="{{ $domain->slug }}">
                            <div class="domain-card-visual">
                                @if ($domain->image)
                                    @include('partials.cover', [
                                        'url' => media($domain->image),
                                        'alt' => $domain->title,
                                        'w' => 700, 'h' => 500,
                                    ])
                                @else
                                @endif
                            </div>

                            <div class="domain-card-body">
                                <h2>
                                    {{ $domain->title }}
                                </h2>

                                @if ($domain->subtitle)
                                    <p class="domain-subtitle">{{ $domain->subtitle }}</p>
                                @endif

                                @if ($domain->headline())
                                    <p class="domain-text">{{ $domain->headline() }}</p>
                                @endif

                                {{-- Les marques que nous représentons dans ce domaine --}}
                                @if ($brands->isNotEmpty())
                                    <div class="domain-brands">
                                        <span class="domain-tags-label">{{ __('site.marques_referencees') }}</span>
                                        <ul class="domain-brand-list">
                                            @foreach ($brands as $brand)
                                                @php
                                                    $ranges = collect(LineList::toPairs($brand->pivot->ranges));
                                                @endphp
                                                <li>
                                                    <span class="domain-brand-name" translate="no">
                                                        @if ($brand->logo)
                                                            <img src="{{ media($brand->logo) }}" alt="{{ $brand->name }}"
                                                                 width="96" height="34" loading="lazy" decoding="async">
                                                        @else
                                                            <b>{{ $brand->name }}</b>
                                                        @endif
                                                    </span>
                                                    @if ($ranges->isNotEmpty())
                                                        <span class="domain-brand-ranges">
                                                            {{ $ranges->pluck('title')->implode(' · ') }}
                                                        </span>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                {{-- Ce que contient le domaine, en un coup d'œil --}}
                                @if ($equipments !== [])
                                    <div class="domain-tags-block">
                                        <span class="domain-tags-label">{{ __('site.ce_que_nous_fournissons') }}</span>
                                        <ul class="domain-tags">
                                            @foreach (array_slice($equipments, 0, 6) as $item)
                                                <li>{{ $item['title'] }}</li>
                                            @endforeach
                                            @if (count($equipments) > 6)
                                                <li class="domain-tag-more">+ {{ count($equipments) - 6 }} autres</li>
                                            @endif
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- Un seul appel à l'action, en fin de page --}}
    <section class="dom-services">
        <div class="wrap">
            <div class="sec-head center">
                <h2>{{ __('site.nous_ne_livrons_pas_seulement_du_materiel') }}</h2>
                <p>Quel que soit le domaine, l'équipement s'accompagne de l'installation,
                   de la formation de vos équipes et d'une maintenance assurée dans la durée.</p>
            </div>
            <div class="center">
                <a class="btn btn-primary" href="{{ lroute('contact') }}">{{ __('site.parler_de_mon_projet') }}</a>
                <a class="btn btn-line" href="{{ lroute('services') }}">{{ __('site.voir_nos_services') }}</a>
            </div>
        </div>
    </section>

@endsection
