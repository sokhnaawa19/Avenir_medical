@extends('admin.layout')

@section('title', 'Traductions')

@section('content')

    <div class="page-head">
        <p>
            Les traductions sont générées automatiquement, puis corrigeables ici.
            Un champ vide affichera le texte français.
        </p>
    </div>

    {{-- Choix de la rubrique --}}
    <div class="shop-tools" style="margin-bottom:18px">
        @foreach ($rubriques as $cle => $rubrique)
            <a class="pill @if ($group === $cle) on @endif"
               href="{{ route('admin.translations.index', ['group' => $cle, 'locale' => $locale]) }}">
                {{ $rubrique['label'] }}
            </a>
        @endforeach
    </div>

    <form method="POST" action="{{ route('admin.translations.update', $group) }}">
        @csrf
        @method('PUT')
        <input type="hidden" name="locale" value="{{ $locale }}">

        @if ($elements->isEmpty())
            <div class="empty"><span>🌍</span>Aucun contenu à traduire dans cette rubrique.</div>
        @else
            @foreach ($elements as $element)
                @php
                    $champs = collect($element->translatableFields())
                        ->filter(fn ($champ) => filled($element->raw($champ)));
                @endphp

                @continue($champs->isEmpty())

                <div class="card" style="margin-bottom:16px">
                    <h2 style="font-size:1rem">
                        @if ($estReglage)
                            {{ $element->key }}
                            <small style="color:var(--muted);font-weight:400">— {{ $element->group }}</small>
                        @else
                            {{ $element->raw('title') ?? $element->raw('name') ?? 'Élément #'.$element->id }}
                        @endif
                    </h2>

                    @foreach ($champs as $champ)
                        @php
                            $original = (string) $element->raw($champ);
                            $traduction = $element->translationFor($champ, $locale);
                            $auto = $element->translations->firstWhere('field', $champ)?->is_automatic ?? true;
                            $long = mb_strlen($original) > 90;
                        @endphp

                        <div class="trad-row">
                            <div class="trad-source">
                                <span class="trad-label">Français · {{ $champ }}</span>
                                <p>{{ $original }}</p>
                            </div>

                            <div class="trad-target">
                                <span class="trad-label">
                                    {{ strtoupper($locale) }}
                                    @if (filled($traduction))
                                        <em class="trad-tag {{ $auto ? '' : 'is-manual' }}">
                                            {{ $auto ? 'automatique' : 'corrigé' }}
                                        </em>
                                    @else
                                        <em class="trad-tag is-missing">à traduire</em>
                                    @endif
                                </span>

                                @if ($long)
                                    <textarea class="input" rows="3"
                                              name="traductions[{{ $element->id }}][{{ $champ }}]"
                                              placeholder="Laissez vide pour afficher le français">{{ $traduction }}</textarea>
                                @else
                                    <input class="input" type="text"
                                           name="traductions[{{ $element->id }}][{{ $champ }}]"
                                           value="{{ $traduction }}"
                                           placeholder="Laissez vide pour afficher le français">
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach

            <div class="form-actions" style="position:sticky;bottom:0;background:#fff;padding:16px;border-radius:14px;box-shadow:0 -6px 20px rgba(12,59,76,.08)">
                <button class="btn btn-primary" type="submit">💾 Enregistrer les traductions</button>
            </div>
        @endif
    </form>

    {{ $elements->links() }}

@endsection
