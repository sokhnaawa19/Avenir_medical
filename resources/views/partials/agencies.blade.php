{{--
    Notre développement.

    Règle importante : on ne présente JAMAIS un projet comme une réalité.
    Ce qui existe (agences ouvertes) et ce qui est prévu (ambition)
    sont clairement séparés et étiquetés.

    Variable : $agencies
--}}
@php
    use App\Models\Agency;

    $ouvertes = $agencies->where('status', Agency::STATUT_OUVERTE)->values();
    $enCours = $agencies->where('status', Agency::STATUT_EN_COURS)->values();
    $projets = $agencies->where('status', Agency::STATUT_PROJET)->values();

    $projetsSenegal = $projets->filter(fn ($a) => $a->isInSenegal())->values();
    $projetsSousRegion = $projets->reject(fn ($a) => $a->isInSenegal())->values();
    $horizon = $projets->pluck('opening_year')->filter()->max();
@endphp

<section class="expansion-section" id="developpement">
    <div class="wrap">
        <div class="sec-head center">
            <span class="eyebrow">Notre développement.</span>
            <h2>{{ setting('expansion_title') }}</h2>
            <p>{{ setting('expansion_text') }}</p>
        </div>

        {{-- Ce qui existe aujourd'hui --}}
        @if ($ouvertes->isNotEmpty() || $enCours->isNotEmpty())
            <h3 class="expansion-title">{{ __('site.aujourd_hui') }}</h3>

            <div class="expansion-grid">
                @foreach ($ouvertes->merge($enCours) as $agence)
                    <article class="agency-card is-{{ str_replace(' ', '-', $agence->status) }}">
                        <div class="agency-head">
                            <span class="agency-pin" aria-hidden="true">@include('partials.icon', ['name' => $agence->isInSenegal() ? 'pin' : 'globe', 'size' => 32])</span>
                            <div>
                                <h4>{{ $agence->name }}</h4>
                                <small>{{ collect([$agence->region, $agence->country])->filter()->implode(' · ') }}</small>
                            </div>
                        </div>

                        <span class="badge-soft badge-{{ $agence->statusColor() }}">{{ $agence->statusLabel() }}</span>
                        @if ($agence->opening_year)<span class="agency-year">{{ $agence->opening_year }}</span>@endif

                        @if ($agence->description)<p>{{ $agence->description }}</p>@endif

                        @if ($agence->address || $agence->phone)
                            <div class="agency-contact">
                                @if ($agence->address)<span>{{ $agence->address }}</span>@endif
                                @if ($agence->phone)
                                    <a href="tel:{{ preg_replace('/\s+/', '', $agence->phone) }}">{{ $agence->phone }}</a>
                                @endif
                            </div>
                        @endif
                    </article>
                @endforeach
            </div>
        @endif

        {{-- Ce qui est prévu : clairement annoncé comme une ambition --}}
        @if ($projets->isNotEmpty())
            <div class="ambition-block">
                <div class="ambition-head">
                    <span class="ambition-label">{{ __('site.notre_ambition') }}</span>
                    <h3>
                        {{ setting('ambition_title') }}
                        @if ($horizon)<span class="ambition-horizon">à l'horizon {{ $horizon }}</span>@endif
                    </h3>
                    <p>{{ setting('ambition_text') }}</p>
                </div>

                <div class="ambition-lists">
                    @if ($projetsSenegal->isNotEmpty())
                        <div class="ambition-col">
                            <h4>{{ __('site.regions_du_senegal') }}</h4>
                            <ul class="ambition-list">
                                @foreach ($projetsSenegal as $projet)
                                    <li>
                                        <b>{{ $projet->name }}</b>
                                        @if ($projet->opening_year)<span>{{ $projet->opening_year }}</span>@endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if ($projetsSousRegion->isNotEmpty())
                        <div class="ambition-col">
                            <h4>@include('partials.icon', ['name' => 'globe']) Sous-région</h4>
                            <ul class="ambition-list">
                                @foreach ($projetsSousRegion as $projet)
                                    <li>
                                        <b>{{ $projet->name }}</b>
                                        <small>{{ $projet->country }}</small>
                                        @if ($projet->opening_year)<span>{{ $projet->opening_year }}</span>@endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

                <p class="ambition-note">
                    Ces implantations sont des projets de développement, à des stades d'avancement différents.
                </p>
            </div>
        @endif
    </div>
</section>
