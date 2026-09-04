{{-- Bloc vidéo : lien YouTube/Vimeo OU fichier envoyé. Variable : $model --}}
@php
    $videoValue = $model->video_url;
    $isLink = filled($videoValue) && str_starts_with((string) $videoValue, 'http');
    $isFile = filled($videoValue) && ! $isLink;

    // Limite reelle du serveur : la plus petite des deux valeurs PHP.
    $toBytes = function (string $value): int {
        $value = trim($value);
        $unit = strtolower(substr($value, -1));
        $number = (int) $value;

        return match ($unit) {
            'g' => $number * 1024 * 1024 * 1024,
            'm' => $number * 1024 * 1024,
            'k' => $number * 1024,
            default => $number,
        };
    };

    $limitBytes = min($toBytes((string) ini_get('upload_max_filesize')), $toBytes((string) ini_get('post_max_size')));
    $limitMo = max(1, (int) round($limitBytes / 1048576));
    $limitFaible = $limitMo < 20;
@endphp

<div class="field">
    <label for="video_url">🎬 Vidéo (facultatif)</label>

    @if ($isFile)
        <p class="hint" style="margin-bottom:10px">
            ✅ Une vidéo est déjà en ligne :
            <a class="link" href="{{ media($videoValue) }}" target="_blank" rel="noopener">la voir</a>
        </p>
    @endif

    <input class="input" type="text" id="video_url" name="video_url"
           value="{{ old('video_url', $isLink ? $videoValue : '') }}"
           placeholder="https://www.youtube.com/watch?v=…">
    <span class="hint">Lien YouTube ou Vimeo, si vous en avez un.</span>

    <div style="margin-top:14px">
        <label for="video_file" style="font-weight:600;font-size:.86rem">…ou envoyez un fichier vidéo</label>
        <input class="input" type="file" id="video_file" name="video_file" accept="video/mp4,video/webm">
        <span class="hint">
            Formats acceptés : MP4, WEBM, MOV. Taille maximale acceptée par votre serveur :
            <b>{{ $limitMo }} Mo</b>.
            Les vidéos de plus de 3 Mo sont automatiquement allégées à l'envoi
            (1280 pixels de large), sans que vous ayez rien à préparer.
        </span>

        @if ($limitFaible)
            <div class="alert alert-error" style="margin-top:10px">
                ⚠️ Votre serveur n'accepte que <b>{{ $limitMo }} Mo</b> : c'est trop peu pour une vidéo.
                Demandez à votre hébergeur (ou modifiez <code>php.ini</code>) d'augmenter
                <code>upload_max_filesize</code> et <code>post_max_size</code> à 128M.
                En attendant, utilisez un lien YouTube.
            </div>
        @endif
    </div>

    @if (filled($videoValue))
        <label class="check" style="margin-top:12px">
            <input type="checkbox" name="remove_video" value="1">
            Supprimer la vidéo actuelle
        </label>
    @endif

    @error('video_file')<span class="err">{{ $message }}</span>@enderror
    @error('video_url')<span class="err">{{ $message }}</span>@enderror
</div>
