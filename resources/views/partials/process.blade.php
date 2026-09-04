{{--
    Le parcours d'accompagnement : ce qui relie les services,
    les formations et le SAV en une seule promesse.
    Variable : $steps
--}}
@if ($steps->isNotEmpty())
    <section class="process-section reveal" id="parcours">
        <div class="wrap">
            <div class="sec-head center">
                <span class="eyebrow">{{ setting('process_eyebrow') }}</span>
                <h2>{{ setting('process_title') }}</h2>
                <p>{{ setting('process_text') }}</p>
            </div>

            <ol class="process-steps">
                @foreach ($steps as $index => $step)
                    <li class="process-step">
                        <div class="process-marker">
                            <span class="process-number">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                            @if (! $loop->last)
                                <span class="process-line" aria-hidden="true"></span>
                            @endif
                        </div>

                        <div class="process-content">
                            <h3>
                                @if ($step->icon)<span class="process-icon">@include('partials.icon-from', ['emoji' => $step->icon, 'size' => 30])</span>@endif
                                {{ $step->title }}
                            </h3>

                            @if ($step->subtitle)
                                <p class="process-subtitle">{{ $step->subtitle }}</p>
                            @endif

                            @if ($step->description)
                                <p class="process-text">{{ $step->description }}</p>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ol>

            @if (setting('process_conclusion'))
                <p class="process-conclusion">{{ setting('process_conclusion') }}</p>
            @endif
        </div>
    </section>
@endif
