@extends('layouts.public')

@section('content')

    @php
        $heroImage = setting_image('hero_image');
        $heroTitle = e((string) setting('hero_title'));
        $highlight = e((string) setting('hero_highlight'));
        $heroTitleHtml = filled($highlight)
            ? str_replace($highlight, '<span>'.$highlight.'</span>', $heroTitle)
            : $heroTitle;
    @endphp

    {{-- Grande image d'accueil --}}
    <section class="hero">
        @if ($heroImage)
            <img class="hero-bg" src="{{ $heroImage }}" alt="" width="1600" height="900"
                 fetchpriority="high" decoding="async">
            <div class="hero-overlay"></div>
        @endif

        <div class="wrap hero-inner">
            <span class="eyebrow">{{ setting('hero_eyebrow') }}</span>
            <h1>{!! $heroTitleHtml !!}</h1>
            <p>{{ setting('hero_text') }}</p>

            <div class="hero-actions btn-row">
                @if (setting('hero_btn1_label'))
                    <a class="btn btn-primary" href="{{ setting('hero_btn1_url', '/entreprise') }}">{{ setting('hero_btn1_label') }}</a>
                @endif
                @if (setting('hero_btn2_label'))
                    <a class="btn btn-line" href="{{ setting('hero_btn2_url', '/contact') }}">{{ setting('hero_btn2_label') }}</a>
                @endif
            </div>

  <div class="hero-badges">
 
    <div class="hb">
        <span class="hb-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"
                 stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/>
                <path d="m9 12 2 2 4-4"/>
            </svg>
        </span>
        {{ __('site.materiel_neuf_certifie') }}
    </div>
 
    <div class="hb">
        <span class="hb-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"
                 stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
            </svg>
        </span>
        {{ __('site.maintenance_sur_site') }}
    </div>
 
    <div class="hb">
        <span class="hb-icon">
             <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"
                 stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <rect x="2" y="7" width="20" height="13" rx="2"/>
              <path d="M8 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
              <path d="M12 11v5M9.5 13.5h5"/>
             </svg>
        </span>
        {{ __('site.ingenieurs_biomedicaux') }}
    </div>
 
</div>
        </div>
    </section>

    <div class="wrap" style="padding-top:26px">
        @include('partials.flash')
    </div>

    {{-- Présentation : image à gauche, texte et chiffres à droite --}}
    <section id="pres" class="reveal">
        <div class="wrap split">
            <div class="split-media">
                @if (setting_image('about_image_1'))
                    <img src="{{ setting_image('about_image_1') }}" alt="" width="800" height="600"
                         loading="lazy" decoding="async">
                @else
                    <div class="collage">
                        @foreach (['about_image_1', 'about_image_2', 'about_image_3'] as $image)
                            <div class="img" @if (setting_image($image)) style="background-image:url('{{ setting_image($image) }}')" @endif></div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="split-text">
                <span class="eyebrow">{{ setting('about_eyebrow') }}</span>
                <h2>{{ setting('about_title') }}</h2>
                <p>{{ setting('about_text') }}</p>

                <div class="split-figures">
                    @foreach ([1, 2, 3, 4] as $i)
                        @if (setting('stat_'.$i.'_value'))
                            <div class="split-figure">
                                <b>{{ setting('stat_'.$i.'_value') }}</b>
                                <small>{{ setting('stat_'.$i.'_label') }}</small>
                            </div>
                        @endif
                    @endforeach
                </div>

                <a class="btn btn-primary" href="{{ lroute('company') }}">{{ __('site.decouvrir_avenir_medical') }}</a>
            </div>
        </div>
    </section>

    {{-- Domaines d'intervention --}}
    @if ($domains->isNotEmpty())
        <section class="domains reveal band-soft" id="domaines">
            <div class="wrap">
                <div class="sec-head center">
                    <span class="eyebrow">{{ setting('domains_eyebrow') }}</span>
                    <h2>{{ setting('domains_title') }}</h2>
                    <p>{{ setting('domains_text') }}</p>
                </div>

                <div class="dom-grid">
                    @foreach ($domains as $domain)
                        <a class="dom" href="{{ lroute('domains') }}#{{ $domain->slug }}">
                            @include('partials.cover', ['url' => media($domain->image), 'alt' => $domain->title, 'w' => 800, 'h' => 600])

                            <div class="cap">
                                @if ($domain->icon)
                                    <span class="dom-icon" aria-hidden="true">@include('partials.icon-from', ['emoji' => $domain->displayIcon(), 'size' => 32])</span>
                                @endif

                                <h3>{{ $domain->title }}</h3>
                                <small>{{ $domain->subtitle }}</small>

                                {{-- Ce que le domaine contient concrètement --}}
                                @php
                                    // Chaque équipement est un couple titre / description :
                                    // on n'affiche que le titre sur la carte.
                                    $equipements = collect($domain->equipmentList())
                                        ->map(fn ($item) => is_array($item) ? ($item['title'] ?? null) : $item)
                                        ->filter()
                                        ->take(3);
                                @endphp

                                @if ($equipements->isNotEmpty())
                                    <ul class="dom-tags">
                                        @foreach ($equipements as $equipement)
                                            <li>{{ \Illuminate\Support\Str::limit($equipement, 34) }}</li>
                                        @endforeach
                                    </ul>
                                @endif

                                {{-- Les marques du domaine, avec leur logo --}}
                                @php $marques = $domain->brandList()->take(2); @endphp

                                @if ($marques->isNotEmpty())
                                    <span class="dom-brands">
                                        @foreach ($marques as $marque)
                                            <span class="dom-brand" translate="no">
                                                @if ($marque->logo)
                                                    <img src="{{ media($marque->logo) }}" alt="{{ $marque->name }}"
                                                         width="70" height="28" loading="lazy" decoding="async">
                                                @else
                                                    {{ $marque->name }}
                                                @endif
                                            </span>
                                        @endforeach
                                    </span>
                                @endif

                                <span class="dom-more">
                                    {{ __('site.decouvrir') }}
                                    @if ($domain->products_count ?? false)
                                        · {{ $domain->products_count }} {{ $domain->products_count > 1 ? __('site.equipements') : __('site.equipement') }}
                                    @endif
                                    →
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="center mt-3">
                    <a class="btn btn-primary" href="{{ lroute('domains') }}">
                        {{ __('site.explorer_tous_nos_domaines') }}
                    </a>
                </div>
            </div>
        </section>
    @endif

    {{-- Le parcours d'accompagnement --}}
    @include('partials.process', ['steps' => $steps])

    {{-- Vidéo de présentation --}}
    @if (settings()->boolean('home_video_enabled') && (setting('home_video_url') || setting('home_video_file')))
        <section class="video-section reveal">
            <div class="wrap video-grid">
                @include('partials.video', [
                    'url' => setting('home_video_url') ?: setting('home_video_file'),
                    'poster' => setting_image('home_video_poster'),
                    'title' => setting('home_video_title'),
                ])

                <div class="sec-head" style="margin-bottom:0">
                    <span class="eyebrow">{{ __('site.en_video') }}</span>
                    <h2>{{ setting('home_video_title') }}</h2>
                    <p>{{ setting('home_video_text') }}</p>
                    <a class="btn btn-primary mt-3" href="{{ lroute('company') }}">{{ __('site.decouvrir_l_entreprise') }}</a>
                </div>
            </div>
        </section>
    @endif


    {{-- Partenariats exclusifs --}}
    @if ($exclusives->isNotEmpty())
        <section class="reveal exclusive-band band-white">
            <div class="wrap">
                <div class="sec-head center">
                    <span class="eyebrow">{{ __('site.representation_exclusive') }}</span>
                    <h2>{{ setting('exclusive_title') }}</h2>
                    <p>{{ setting('exclusive_text') }}</p>
                </div>

                <div class="exclusive-strip">
                    @foreach ($exclusives as $partner)
                        <div class="exclusive-chip">
                            <span class="exclusive-star" aria-hidden="true">@include('partials.icon', ['name' => 'star', 'size' => 24])</span>
                            <span class="exclusive-chip-logo">
                                @if ($partner->logo)
                                    <img src="{{ media($partner->logo) }}" alt="{{ $partner->name }}"
                                         width="120" height="60" loading="lazy" decoding="async">
                                @else
                                    <b translate="no">{{ $partner->name }}</b>
                                @endif
                            </span>
                            <small>{{ $partner->exclusivityLabel() }}</small>
                        </div>
                    @endforeach
                </div>

                <div class="center mt-3">
                    <a class="btn btn-primary" href="{{ lroute('services') }}#exclusivites">{{ __('site.nos_partenariats_exclusifs') }}</a>
                </div>
            </div>
        </section>
    @endif

    {{-- Des techniciens formés chez les fabricants --}}
    @if ($trainings->isNotEmpty())
        <section class="reveal band-soft">
            <div class="wrap">
                <div class="sec-head center">
                    <span class="eyebrow">{{ __('site.formation_continue') }}</span>
                    <h2>{{ setting('training_title') }}</h2>
                    <p>{{ setting('training_text') }}</p>
                </div>

                <div class="training-grid">
                    @foreach ($trainings as $training)
                        @php
                            // La photo mise en avant, ou à défaut la première de la galerie.
                            $illustration = $training->image ?: $training->photos->first()?->image;
                        @endphp

                        <article class="training-card">
                            <div class="training-visual">
                                @if ($illustration)
                                    <img src="{{ media($illustration) }}" alt="{{ $training->title }}"
                                         width="600" height="400" loading="lazy" decoding="async">
                                @else
                                    <span class="training-icon">@include('partials.icon', ['name' => 'graduation', 'size' => 48])</span>
                                @endif

                                @if ($training->country)
                                    <span class="training-country" translate="no">@include('partials.icon', ['name' => 'pin']) {{ $training->country }}</span>
                                @endif
                            </div>

                            <div class="training-body">
                                @if ($training->year)<time>{{ $training->year }}</time>@endif

                                <h3>{{ $training->title }}</h3>

                                @if ($training->organism)
                                    <p class="training-organism" translate="no">{{ $training->organism }}</p>
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

                <div class="center mt-3">
                    <a class="btn btn-primary" href="{{ lroute('services') }}#formations">{{ __('site.notre_expertise_technique') }}</a>
                </div>
            </div>
        </section>
    @endif

    @include('partials.references', ['establishments' => $establishments])


    {{-- Une réalisation mise en avant --}}
    @if ($project)
        <section class="reveal band-white">
            <div class="wrap">
                @include('partials.showcase', ['project' => $project])
            </div>
        </section>
    @endif

    {{-- Galerie photos --}}
    @if ($photos->isNotEmpty())
        <section class="reveal band-soft">
            <div class="wrap">
                <div class="sec-head center">
                    <span class="eyebrow">{{ __('site.en_images') }}</span>
                    <h2>{{ setting('gallery_page_title') }}</h2>
                    <p>{{ setting('gallery_page_text') }}</p>
                </div>

                <div class="home-gallery">
                    @foreach ($photos as $photo)
                        <a class="home-gallery-item" href="{{ lroute('gallery') }}"
                           title="{{ $photo->title }}">
                            <img src="{{ media($photo->image) }}" alt="{{ $photo->title }}"
                                 width="400" height="400" loading="lazy" decoding="async">
                            @if ($photo->title)
                                <span>{{ $photo->title }}</span>
                            @endif
                        </a>
                    @endforeach
                </div>

                <div class="center mt-3">
                    <a class="btn btn-primary" href="{{ lroute('gallery') }}">{{ __('site.voir_toute_la_galerie') }}</a>
                </div>
            </div>
        </section>
    @endif
    {{-- Notre ambition régionale --}}
    @if ($agencies->isNotEmpty())
        @php
            $ouvertes = $agencies->where('status', \App\Models\Agency::STATUT_OUVERTE);
            $projets = $agencies->where('status', '!=', \App\Models\Agency::STATUT_OUVERTE);
            $horizon = $projets->pluck('opening_year')->filter()->max();
        @endphp

        <section class="reveal ambition-band">
            <div class="wrap">
                <div class="ambition-band-inner">
                    <div>
                        <span class="eyebrow">Notre ambition</span>
                        <h2>{{ setting('ambition_title') }}</h2>
                        <p>{{ setting('ambition_text') }}</p>
                    </div>

                    <div class="ambition-figures">
                        <div class="ambition-figure">
                            <b>{{ $ouvertes->count() }}</b>
                            <small>{{ $ouvertes->count() > 1 ? 'agences ouvertes' : 'agence ouverte' }}</small>
                        </div>
                        <div class="ambition-figure is-goal">
                            <b>+{{ $projets->count() }}</b>
                            <small>implantations prévues @if ($horizon) d'ici {{ $horizon }} @endif</small>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- Produits mis en avant --}}
    @if (settings()->boolean('shop_enabled', true) && $featured->isNotEmpty())
        <section class="reveal bg-section {{ setting_image('shop_bg') ? 'has-bg' : 'band-soft' }}">
            @if (setting_image('shop_bg'))
                <img class="bg-section-img" src="{{ setting_image('shop_bg') }}" alt=""
                     width="1600" height="900" loading="lazy" decoding="async">
                <div class="bg-section-veil"></div>
            @endif

            <div class="wrap">
                <div class="sec-head center">
                    <span class="eyebrow">{{ __('site.boutique_en_ligne') }}</span>
                    <h2>{{ setting('shop_title') }}</h2>
                    <p>{{ setting('shop_text') }}</p>
                </div>

                <div class="grid-4">
                    @foreach ($featured as $product)
                        @include('partials.product-card', ['product' => $product])
                    @endforeach
                </div>

                <div class="center mt-3">
                    <a class="btn btn-primary" href="{{ lroute('shop.index') }}">{{ __('site.voir_toute_la_boutique') }}</a>
                </div>
            </div>
        </section>
    @endif

    {{-- Derniers articles --}}
    @if ($posts->isNotEmpty())
        <section class="reveal bg-section {{ setting_image('blog_bg') ? 'has-bg' : 'band-white' }}" id="blog">
            @if (setting_image('blog_bg'))
                <img class="bg-section-img" src="{{ setting_image('blog_bg') }}" alt=""
                     width="1600" height="900" loading="lazy" decoding="async">
                <div class="bg-section-veil"></div>
            @endif

            <div class="wrap">
                <div class="sec-head center">
                    <span class="eyebrow">{{ __('site.blog_actualites') }}</span>
                    <h2>{{ setting('blog_title') }}</h2>
                    <p>{{ setting('blog_text') }}</p>
                </div>

                <div class="blog-grid">
                    @foreach ($posts as $post)
                        @include('partials.post-card', ['post' => $post])
                    @endforeach
                </div>

                <div class="center mt-3">
                    <a class="btn btn-primary" href="{{ lroute('blog.index') }}">{{ __('site.voir_toutes_les_actualites') }}</a>
                </div>
            </div>
        </section>
    @endif

    {{-- Où nous trouver --}}
    @include('partials.map')

@endsection
