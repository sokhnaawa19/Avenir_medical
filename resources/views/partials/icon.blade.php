{{--
    Icône vectorielle.

    Utilisation :  @include('partials.icon', ['name' => 'phone'])
    Variables   :  $name (obligatoire), $size (18 par défaut), $class

    Les icônes sont dessinées au trait et prennent la couleur du texte
    qui les entoure (currentColor) : elles s'adaptent donc à tous les fonds.
--}}
@php
    $taille = $size ?? 26;

    $traces = [
        // --- Contact ---
        'phone' => '<path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 1.9.7 2.8a2 2 0 0 1-.5 2.1L8.1 9.9a16 16 0 0 0 6 6l1.3-1.2a2 2 0 0 1 2.1-.5c.9.3 1.8.6 2.8.7a2 2 0 0 1 1.7 2z"/>',
        'mail' => '<rect x="2" y="4" width="20" height="16" rx="2"/><path d="m2 7 10 6 10-6"/>',
        'pin' => '<path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0z"/><circle cx="12" cy="10" r="3"/>',
        'globe' => '<circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15 15 0 0 1 0 20 15 15 0 0 1 0-20z"/>',
        'clock' => '<circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>',

        // --- Boutique ---
        'cart' => '<circle cx="9" cy="20" r="1.5"/><circle cx="18" cy="20" r="1.5"/><path d="M2 3h3l2.6 12.4a2 2 0 0 0 2 1.6h7.7a2 2 0 0 0 2-1.6L21 7H6"/>',
        'box' => '<path d="m21 8-9-5-9 5v8l9 5 9-5z"/><path d="m3 8 9 5 9-5M12 13v8"/>',
        'truck' => '<path d="M1 4h13v12H1zM14 8h4l3 3v5h-7z"/><circle cx="6" cy="19" r="1.6"/><circle cx="18" cy="19" r="1.6"/>',
        'cash' => '<rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="12" cy="12" r="2.5"/><path d="M6 12h.01M18 12h.01"/>',
        'trash' => '<path d="M3 6h18M8 6V4h8v2M6 6l1 14h10l1-14"/>',

        // --- Santé et technique ---
        'hospital' => '<path d="M4 21V8l8-5 8 5v13"/><path d="M9 21v-5h6v5M12 9v4M10 11h4"/>',
        'wrench' => '<path d="M14.7 6.3a4 4 0 1 0 5 5L21 12l-9 9-3-3 9-9z"/>',
        'graduation' => '<path d="m22 9-10-5L2 9l10 5 10-5z"/><path d="M6 11v5c0 1.5 3 3 6 3s6-1.5 6-3v-5"/>',
        'user' => '<circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 3.6-6 8-6s8 2 8 6"/>',
        'users' => '<circle cx="9" cy="8" r="3.5"/><path d="M2 21c0-3.5 3-5.5 7-5.5s7 2 7 5.5"/><path d="M17 8.5a3 3 0 1 0 0-1M22 21c0-3-2-4.5-4.5-4.8"/>',
        'handshake' => '<path d="M12 8 9.5 5.5a2 2 0 0 0-2.8 0L2 10l4 4"/><path d="m12 8 2.5-2.5a2 2 0 0 1 2.8 0L22 10l-4 4"/><path d="m8 14 2.5 2.5a2 2 0 0 0 2.8 0l1-1 2 2a1.6 1.6 0 0 0 2.3-2.3"/><path d="M12 8 9 11a1.6 1.6 0 0 0 2.3 2.3L13 12"/>',
        'building' => '<rect x="3" y="3" width="8" height="18" rx="1"/><rect x="13" y="8" width="8" height="13" rx="1"/><path d="M6 7h2M6 11h2M6 15h2M16 12h2M16 16h2"/>',

        // --- Divers ---
        'star' => '<path d="m12 3 2.8 5.7 6.2.9-4.5 4.4 1 6.2-5.5-3-5.5 3 1-6.2L3 9.6l6.2-.9z"/>',
        'search' => '<circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/>',
        'camera' => '<path d="M3 7h3l2-2h8l2 2h3v12H3z"/><circle cx="12" cy="13" r="4"/>',
        'news' => '<path d="M4 4h13v16H4z"/><path d="M17 8h3v10a2 2 0 0 1-3 2"/><path d="M7 8h7M7 12h7M7 16h4"/>',
        'menu' => '<path d="M3 6h18M3 12h18M3 18h18"/>',
        'check' => '<path d="m4 12 5 5L20 6"/>',
        'alert' => '<path d="M12 3 2 20h20z"/><path d="M12 10v5M12 18h.01"/>',
        'lock' => '<rect x="4" y="10" width="16" height="11" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/>',
        'settings' => '<circle cx="12" cy="12" r="3.5"/><path d="M12 2v3M12 19v3M2 12h3M19 12h3M5 5l2 2M17 17l2 2M19 5l-2 2M7 17l-2 2"/>',
        'bulb' => '<path d="M9 18h6M10 21h4"/><path d="M12 3a6 6 0 0 1 4 10.5V16H8v-2.5A6 6 0 0 1 12 3z"/>',
        'list' => '<path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/>',
        'party' => '<path d="m3 21 6-16 9 9z"/><path d="M15 4h.01M19 8h.01M20 3h.01"/>',
        'wave' => '<path d="M12 3v9M8 6v7M16 7v5M4 10v3M20 10v3"/>',
        'gem' => '<path d="M6 3h12l3 6-9 12L3 9z"/><path d="M3 9h18M9 3 7 9l5 12M15 3l2 6-5 12"/>',
        'signal' => '<path d="M12 16a4 4 0 1 0 0-8 4 4 0 0 0 0 8z" stroke-width="1.6"/><path d="M5.6 5.6a9 9 0 0 0 0 12.8M18.4 5.6a9 9 0 0 1 0 12.8M2.8 2.8a13 13 0 0 0 0 18.4M21.2 2.8a13 13 0 0 1 0 18.4"/>',
        'bolt' => '<path d="M13 2 4 14h7l-1 8 9-12h-7z"/>',
        'target' => '<circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="5"/><circle cx="12" cy="12" r="1.4"/>',
        'heart' => '<path d="M12 20s-8-5-8-10a4.5 4.5 0 0 1 8-2.8A4.5 4.5 0 0 1 20 10c0 5-8 10-8 10z"/>',
        'chart' => '<path d="M4 20V10M10 20V4M16 20v-7M22 20H2"/>',
        'pill' => '<rect x="3" y="9" width="18" height="7" rx="3.5" transform="rotate(-45 12 12)"/><path d="m9 9 6 6"/>',
        'thermometer' => '<path d="M14 14V5a2 2 0 1 0-4 0v9a4 4 0 1 0 4 0z"/>',
        'sparkle' => '<path d="m12 3 2 6 6 2-6 2-2 6-2-6-6-2 6-2z"/>',
    ];

    $trace = $traces[$name] ?? null;
@endphp

@if ($trace)
    <svg class="ic-svg {{ $class ?? '' }}"
         width="{{ $taille }}" height="{{ $taille }}" viewBox="0 0 24 24"
         fill="none" stroke="currentColor" stroke-width="1.7"
         stroke-linecap="round" stroke-linejoin="round"
         aria-hidden="true" focusable="false">{!! $trace !!}</svg>
@endif
