{{--
    Affiche l'icône vectorielle correspondant à un émoji enregistré
    dans l'administration (domaines, services, parcours).

    Si l'émoji n'a pas d'équivalent dessiné, il est affiché tel quel :
    l'administrateur reste libre d'en choisir un autre.

    Variables : $emoji (obligatoire), $size
--}}
@php
    $equivalences = [
        '📦' => 'box', '📍' => 'pin', '🎓' => 'graduation', '🛒' => 'cart',
        '⭐' => 'star', '📞' => 'phone', '🔍' => 'search', '🌍' => 'globe',
        '🏥' => 'hospital', '🔧' => 'wrench', '🛠️' => 'wrench', '🛠' => 'wrench',
        '🤝' => 'handshake', '🏢' => 'building', '📷' => 'camera', '📰' => 'news',
        '💡' => 'bulb', '📋' => 'list', '💵' => 'cash', '🚚' => 'truck',
        '👤' => 'user', '👥' => 'users', '👨‍🔧' => 'wrench', '🩺' => 'hospital',
        '🔬' => 'search', '🧪' => 'search', '💉' => 'hospital', '🚑' => 'truck',
        '🦷' => 'hospital', '👁️' => 'search', '👁' => 'search', '🧭' => 'globe',
        '🛡️' => 'lock', '🛡' => 'lock', '✅' => 'check', '⚙️' => 'settings',
        '🏗️' => 'building', '🏗' => 'building', '💎' => 'gem', '📜' => 'news',
        '📡' => 'signal', '🤲' => 'handshake', '⚡' => 'bolt', '🎯' => 'target',
        '❤️' => 'heart', '❤' => 'heart', '🫀' => 'heart', '🦴' => 'hospital',
        '🧑‍⚕️' => 'user', '👩‍⚕️' => 'user', '🏭' => 'building', '📊' => 'chart',
        '💊' => 'pill', '🧬' => 'search', '🌡️' => 'thermometer', '🌡' => 'thermometer',
        '🚨' => 'alert', '🔔' => 'alert', '📐' => 'settings', '🧰' => 'wrench',
    ];

    $cle = trim((string) ($emoji ?? ''));
    $nom = $equivalences[$cle] ?? null;
@endphp

@if ($nom)
    @include('partials.icon', ['name' => $nom, 'size' => $size ?? 18])
@elseif (filled($cle))
    {{ $cle }}
@endif
