@extends('admin.layout')

@section('title', $photo->exists ? 'Modifier la photo' : 'Ajouter une photo')

@section('content')

    <form method="POST" enctype="multipart/form-data"
          action="{{ $photo->exists ? route('admin.photos.update', $photo) : route('admin.photos.store') }}">
        @csrf
        @if ($photo->exists)
            @method('PUT')
        @endif

        <div class="card" style="max-width:680px">
            <div class="field">
                <label for="image">Photo @unless ($photo->exists)<span class="required">*</span>@endunless</label>
                <div class="media-row">
                    <div class="preview" @if ($photo->image) style="background-image:url('{{ media($photo->image) }}')" @endif>
                        @unless ($photo->image) Aucune photo @endunless
                    </div>
                    <input class="input @error('image') invalid @enderror" style="flex:1;min-width:200px"
                           type="file" id="image" name="image" accept="image/*" @unless ($photo->exists) required @endunless>
                </div>
                @if ($photo->exists)<span class="hint">Laissez vide pour garder la photo actuelle.</span>@endif
                @error('image')<span class="err">{{ $message }}</span>@enderror
            </div>

            <div class="grid g2">
                <div class="field">
                    <label for="title">Titre</label>
                    <input class="input" type="text" id="title" name="title" value="{{ old('title', $photo->title) }}"
                           placeholder="Ex : Salon de la santé 2026">
                </div>

                <div class="field">
                    <label for="album">Album</label>
                    <input class="input" type="text" id="album" name="album" list="albums"
                           value="{{ old('album', $photo->album) }}" placeholder="Ex : Événements">
                    <datalist id="albums">
                        <option value="Événements"></option>
                        <option value="Installations"></option>
                        <option value="Formations"></option>
                        <option value="Salons"></option>
                        <option value="Nos équipes"></option>
                    </datalist>
                    <span class="hint">Les albums servent de filtres sur la page galerie.</span>
                </div>
            </div>

            <div class="field">
                <label for="caption">Légende</label>
                <textarea class="input" id="caption" name="caption"
                          placeholder="Une phrase qui décrit la photo.">{{ old('caption', $photo->caption) }}</textarea>
            </div>

            <div class="grid g2">
                <div class="field">
                    <label for="taken_at">Date</label>
                    <input class="input" type="text" id="taken_at" name="taken_at"
                           value="{{ old('taken_at', $photo->taken_at) }}" placeholder="Ex : Mars 2026">
                </div>

                <div class="field">
                    <label for="position">Ordre d'affichage</label>
                    <input class="input" type="number" id="position" name="position"
                           value="{{ old('position', $photo->position ?? 0) }}" min="0">
                </div>
            </div>

            <div class="field">
                <label class="check">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $photo->is_active))>
                    Photo visible dans la galerie
                </label>
            </div>

            <div class="form-actions">
                <button class="btn btn-primary" type="submit">💾 Enregistrer</button>
                <a class="btn btn-line" href="{{ route('admin.photos.index') }}">Annuler</a>
            </div>
        </div>
    </form>

@endsection
