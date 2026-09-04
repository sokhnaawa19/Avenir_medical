{{--
    Galerie de photos d'un contenu (formation, service, référence…).

    Variables : $model (obligatoire), $route (route de suppression),
                $label, $hint

    ATTENTION — Les formulaires de suppression ne sont PAS écrits ici.
    Un <form> imbriqué dans un autre est invalide : le navigateur les
    fusionne, et le champ « DELETE » se retrouve dans le formulaire
    d'édition. Le bouton « Enregistrer » supprimerait alors le contenu.

    Ils sont donc empilés avec @push et rendus en fin de page, hors du
    formulaire. Les boutons y sont reliés par l'attribut « form ».
--}}
<div class="field">
    <label>📷 {{ $label ?? 'Photos' }}</label>
    <span class="hint">{{ $hint ?? "Jusqu'à 12 photos, 6 Mo chacune." }}</span>

    @if ($model->exists && $model->photos->isNotEmpty())
        <div class="photo-admin-grid">
            @foreach ($model->photos as $photo)
                @php $identifiant = 'photo-suppr-'.$photo->id; @endphp

                <div class="photo-admin-item">
                    <img src="{{ media($photo->image) }}" alt="">

                    <button class="btn btn-danger btn-sm"
                            type="submit"
                            form="{{ $identifiant }}"
                            title="Retirer cette photo">×</button>
                </div>

                @push('formulaires-hors-page')
                    <form id="{{ $identifiant }}"
                          method="POST"
                          action="{{ route($route, [$model, $photo]) }}"
                          data-confirm="Cette photo sera définitivement retirée."
                          data-confirm-name="cette photo">
                        @csrf
                        @method('DELETE')
                    </form>
                @endpush
            @endforeach
        </div>
    @endif

    <input class="input" style="margin-top:12px" type="file" name="photos[]" accept="image/*" multiple>
    @error('photos.*')<span class="err">{{ $message }}</span>@enderror
</div>
