{{--
    Image qui remplit son conteneur.
    Variables : $url, $alt, $w, $h, $priority (true pour l'image principale)
--}}
@if (! empty($url))
    <img class="cover {{ $coverClass ?? '' }}"
         src="{{ $url }}"
         alt="{{ $alt ?? '' }}"
         width="{{ $w ?? 800 }}"
         height="{{ $h ?? 600 }}"
         @if (! empty($priority))
             fetchpriority="high" loading="eager"
         @else
             loading="lazy"
         @endif
         decoding="async">
@endif
