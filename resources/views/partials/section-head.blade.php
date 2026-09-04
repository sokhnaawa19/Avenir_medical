{{--
    Titre de section, avec numéro de chapitre facultatif.
    Variables : $number, $eyebrow, $title, $text, $align ('center' par défaut)
--}}
<div class="sec-head {{ $align ?? 'center' }}">
    <span class="eyebrow">
        @if (! empty($number))
            <span class="chapter">{{ $number }}</span>
        @endif
        {{ $eyebrow }}
    </span>

    <h2>{{ $title }}</h2>

    @if (! empty($text))
        <p>{{ $text }}</p>
    @endif
</div>
