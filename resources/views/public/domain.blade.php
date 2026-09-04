@extends('layouts.public')

@section('title', $domain->title.' — '.setting('site_name'))
@section('meta_description', $domain->subtitle ?: str($domain->description)->limit(150))

@section('content')

    @include('partials.page-hero', [
        'title' => $domain->title,
        'text' => $domain->subtitle,
        'crumb' => '<a href="'.lroute('domains').'">'.__('site.nos_domaines').'</a> › '.e($domain->title),
    ])

    <section>
        <div class="wrap">
            @include('partials.flash')

            <div class="domain-intro">
                <div class="domain-intro-text">
                    @if ($domain->description)
                        <p>{{ $domain->description }}</p>
                    @endif

                    <div class="domain-facts">
                        <div class="domain-fact">
                            <b>{{ $products->total() }}</b>
                            <small>{{ $products->total() > 1 ? 'produits disponibles' : 'produit disponible' }}</small>
                        </div>
                        @if ($brands->isNotEmpty())
                            <div class="domain-fact">
                                <b>{{ $brands->count() }}</b>
                                <small>{{ $brands->count() > 1 ? 'marques partenaires' : 'marque partenaire' }}</small>
                            </div>
                        @endif
                    </div>

                    <div class="domain-actions">
                        <a class="btn btn-primary" href="{{ lroute('contact') }}">{{ __('site.demander_un_devis') }}</a>
                        @if (setting('phone_1'))
                            <a class="btn btn-line" href="tel:{{ preg_replace('/\s+/', '', (string) setting('phone_1')) }}">
                                @include('partials.icon', ['name' => 'phone']) {{ setting('phone_1') }}
                            </a>
                        @endif
                    </div>
                </div>

                <div class="domain-intro-media">
                    @if ($domain->video_url)
                        @include('partials.video', [
                            'url' => $domain->video_url,
                            'poster' => media($domain->image),
                            'title' => $domain->title,
                        ])
                    @elseif ($domain->image)
                        <img class="domain-intro-img" src="{{ media($domain->image) }}" alt="{{ $domain->title }}"
                             width="800" height="600" loading="lazy" decoding="async">
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- Les marques présentes dans ce domaine --}}
    @if ($brands->isNotEmpty())
        <section style="padding-top:0">
            <div class="wrap">
                <div class="sec-head">
                    <span class="eyebrow">{{ __('site.les_fabricants') }}</span>
                    <h2>{{ __('site.les_marques_de_ce_domaine') }}</h2>
                </div>

                <div class="partners-grid">
                    @foreach ($brands as $brand)
                        <div class="partner-card">
                            <div class="partner-logo">
                                @if ($brand->logo)
                                    <img src="{{ media($brand->logo) }}" alt="{{ $brand->name }}"
                                         width="160" height="80" loading="lazy" decoding="async">
                                @else
                                    <span class="partner-initials">{{ $brand->initials() }}</span>
                                @endif
                            </div>
                            <span class="partner-name" translate="no">{{ $brand->name }}</span>
                            @if ($brand->country)
                                <small class="partner-country">{{ $brand->country }}</small>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Les produits de ce domaine --}}
    <section style="padding-top:0">
        <div class="wrap">
            <div class="sec-head">
                <span class="eyebrow">{{ __('site.notre_offre') }}</span>
                <h2>{{ __('site.les_equipements_de_ce_domaine') }}</h2>
            </div>

            @if ($products->isEmpty())
                <p class="vide">
                    <span>@include('partials.icon', ['name' => 'box', 'size' => 58])</span>
                    {{ __('site.les_produits_de_ce_domaine_ne_sont_pas_encor') }}
                    <a class="link" href="{{ lroute('contact') }}">'.__('site.contactez_nous').'</a> '.__('site.pour_connaitre_notre_offre').'
                </p>
            @else
                <div class="grid-4">
                    @foreach ($products as $product)
                        @include('partials.product-card', ['product' => $product])
                    @endforeach
                </div>

                {{ $products->links() }}
            @endif
        </div>
    </section>

    {{-- Les autres domaines --}}
    @if ($others->isNotEmpty())
        <section style="padding-top:0">
            <div class="wrap">
                <div class="sec-head">
                    <span class="eyebrow">{{ __('site.voir_aussi') }}</span>
                    <h2>{{ __('site.nos_autres_domaines') }}</h2>
                </div>

                <div class="dom-grid">
                    @foreach ($others as $other)
                        <a class="dom" href="{{ lroute('domain', $other) }}">
                            @include('partials.cover', ['url' => media($other->image), 'alt' => $other->title, 'w' => 800, 'h' => 600])
                            <div class="cap">
                                <h3>{{ $other->title }}</h3>
                                <small>{{ $other->subtitle }}</small>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

@endsection
