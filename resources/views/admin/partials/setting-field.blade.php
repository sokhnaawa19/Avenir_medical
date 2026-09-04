{{-- Affiche un champ de reglage selon son type. Variables : $key, $field --}}
@php
    $type = $field['type'] ?? 'text';
    $value = setting($key);
    $inputName = 'settings.'.$key;
    $oldValue = old('settings.'.$key, $value);
@endphp

<div class="field">
    <label for="setting-{{ $key }}">{{ $field['label'] ?? $key }}</label>

    @switch($type)
        @case('image')
            @php $imageUrl = setting_image($key); @endphp
            <div class="media-row">
                <div class="preview" @if ($imageUrl) style="background-image:url('{{ $imageUrl }}')" @endif>
                    @unless ($imageUrl)Aucune image @endunless
                </div>

                <div style="flex:1;min-width:200px">
                    <input class="input" type="file" id="setting-{{ $key }}" name="files[{{ $key }}]" accept="image/*">

                    @if ($imageUrl)
                        <label class="check" style="margin-top:10px">
                            <input type="checkbox" name="remove[{{ $key }}]" value="1">
                            Supprimer cette image
                        </label>
                    @endif
                </div>
            </div>
            @break

        @case('video')
            @php $videoUrl = setting($key) ? media(setting($key)) : null; @endphp
            <div class="media-row">
                <div class="preview">@if ($videoUrl) 🎬 Vidéo en ligne @else Aucune vidéo @endif</div>

                <div style="flex:1;min-width:200px">
                    <input class="input" type="file" id="setting-{{ $key }}" name="files[{{ $key }}]" accept="video/mp4,video/webm">

                    @if ($videoUrl)
                        <label class="check" style="margin-top:10px">
                            <input type="checkbox" name="remove[{{ $key }}]" value="1">
                            Supprimer cette vidéo
                        </label>
                    @endif
                </div>
            </div>
            @break

        @case('boolean')
            <label class="check">
                <input type="checkbox" name="settings[{{ $key }}]" value="1" @checked(filter_var($oldValue, FILTER_VALIDATE_BOOLEAN))>
                Activer
            </label>
            @break

        @case('textarea')
            <textarea class="input @error($inputName) invalid @enderror" id="setting-{{ $key }}"
                      name="settings[{{ $key }}]">{{ $oldValue }}</textarea>
            @break

        @case('color')
            <div style="display:flex;gap:10px;align-items:center">
                <input class="input" type="color" style="max-width:70px" id="setting-{{ $key }}"
                       name="settings[{{ $key }}]" value="{{ $oldValue ?: '#16657F' }}">
                <span class="hint">{{ $oldValue }}</span>
            </div>
            @break

        @case('number')
            <input class="input @error($inputName) invalid @enderror" type="number" id="setting-{{ $key }}"
                   name="settings[{{ $key }}]" value="{{ $oldValue }}">
            @break

        @default
            <input class="input @error($inputName) invalid @enderror"
                   type="{{ in_array($type, ['email', 'tel', 'url'], true) ? $type : 'text' }}"
                   id="setting-{{ $key }}" name="settings[{{ $key }}]" value="{{ $oldValue }}">
    @endswitch

    @if (! empty($field['help']))
        <span class="hint">{{ $field['help'] }}</span>
    @endif

    @error($inputName)<span class="err">{{ $message }}</span>@enderror
</div>
