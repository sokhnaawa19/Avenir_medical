@extends('layouts.public')

@section('title', 'Le groupe — '.setting('site_name'))
@section('meta_description', "AVENIR MEDICAL, maison mère d'un groupe d'entreprises spécialisées dans la santé.")

@section('content')

    @include('partials.page-hero', [
        'title' => setting('group_title'),
        'text' => setting('group_text'),
        'crumb' => __('site.le_groupe'),
    ])

    {{-- La maison mère et ses entreprises --}}
    <section>
        <div class="wrap">
            @include('partials.flash')

            <div class="group-parent">
                <span class="group-parent-badge">{{ __('site.maison_mere') }}</span>
                <h2 translate="no">{{ setting('site_name') }}</h2>
                <p>{{ setting('group_parent_text') }}</p>
            </div>

            @if ($subsidiaries->isNotEmpty())
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
            @else
                <p class="vide"><span>@include('partials.icon', ['name' => 'building', 'size' => 58])</span>{{ __('site.les_entreprises_du_groupe_seront_bientot_pre') }}</p>
            @endif
        </div>
    </section>

@endsection
