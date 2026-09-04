{{--
    Une réalisation mise en avant : grande photo + détails du projet.
    Variable : $project (un établissement « réalisation phare »)
--}}
@if ($project)
    <div class="showcase">
        <div class="showcase-media">
            @if ($project->image)
                <img src="{{ media($project->image) }}" alt="{{ $project->name }}"
                     width="800" height="600" loading="lazy" decoding="async">
            @elseif ($project->logo)
                <img src="{{ media($project->logo) }}" alt="{{ $project->name }}"
                     width="800" height="600" loading="lazy" decoding="async"
                     style="object-fit:contain;padding:40px;background:#fff">
            @endif

            <span class="showcase-tag">{{ __('site.realisation') }}</span>
        </div>

        <div class="showcase-body">
            <span class="eyebrow">{{ __('site.projet') }}</span>
            <h3>{{ $project->name }}</h3>
            <p class="showcase-place">
                {{ collect([$project->type, $project->location(), $project->year])->filter()->implode(' · ') }}
            </p>

            <dl class="showcase-rows">
                @if ($project->equipmentList() !== [])
                    <div class="showcase-row">
                        <dt>{{ __('site.solutions_fournies') }}</dt>
                        <dd>
                            <ul class="showcase-list">
                                @foreach (array_slice($project->equipmentList(), 0, 5) as $equipement)
                                    <li>{{ $equipement }}</li>
                                @endforeach
                            </ul>
                        </dd>
                    </div>
                @endif

                @if ($project->description)
                    <div class="showcase-row">
                        <dt>{{ __('site.le_projet') }}</dt>
                        <dd>{{ str($project->description)->limit(180) }}</dd>
                    </div>
                @endif

                <div class="showcase-row">
                    <dt>{{ __('site.accompagnement') }}</dt>
                    <dd>{{ __('site.installation_formation_des_equipes_maintenan') }}</dd>
                </div>
            </dl>

            <a class="btn btn-primary btn-sm" href="{{ lroute('references') }}">{{ __('site.voir_toutes_nos_references') }}</a>
        </div>
    </div>
@endif
