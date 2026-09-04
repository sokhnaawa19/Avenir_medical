@extends('admin.layout')

@section('title', $establishment->exists ? 'Modifier la référence' : 'Nouvelle référence')

@section('content')

    <form method="POST" enctype="multipart/form-data"
          action="{{ $establishment->exists ? route('admin.establishments.update', $establishment) : route('admin.establishments.store') }}">
        @csrf
        @if ($establishment->exists)
            @method('PUT')
        @endif

        <div class="grid g2" style="align-items:start">
            <div class="card">
                <h2>L'établissement</h2>

                <div class="field">
                    <label for="name">Nom <span class="required">*</span></label>
                    <input class="input @error('name') invalid @enderror" type="text" id="name" name="name"
                           value="{{ old('name', $establishment->name) }}" required
                           placeholder="Ex : Clinique de la Madeleine">
                    @error('name')<span class="err">{{ $message }}</span>@enderror
                </div>

                <div class="grid g2">
                    <div class="field">
                        <label for="type">Type</label>
                        <input class="input" type="text" id="type" name="type" list="types-etablissement"
                               value="{{ old('type', $establishment->type) }}" placeholder="Ex : Clinique privée">
                        <datalist id="types-etablissement">
                            <option value="Hôpital"></option>
                            <option value="Clinique privée"></option>
                            <option value="Centre de santé"></option>
                            <option value="Cabinet médical"></option>
                            <option value="Laboratoire"></option>
                        </datalist>
                    </div>

                    <div class="field">
                        <label for="year">Année</label>
                        <input class="input" type="text" id="year" name="year"
                               value="{{ old('year', $establishment->year) }}" placeholder="Ex : 2024">
                    </div>
                </div>

                <div class="grid g2">
                    <div class="field">
                        <label for="city">Ville</label>
                        <input class="input" type="text" id="city" name="city"
                               value="{{ old('city', $establishment->city) }}" placeholder="Ex : Dakar">
                    </div>

                    <div class="field">
                        <label for="country">Pays</label>
                        <input class="input" type="text" id="country" name="country"
                               value="{{ old('country', $establishment->country ?: 'Sénégal') }}">
                    </div>
                </div>

                <div class="field">
                    <label for="description">Description</label>
                    <textarea class="input" id="description" name="description"
                              placeholder="En quelques phrases, ce que vous avez réalisé pour cet établissement.">{{ old('description', $establishment->description) }}</textarea>
                </div>

                <div class="field">
                    <label for="equipments">Équipements installés</label>
                    <textarea class="input" id="equipments" name="equipments"
                              placeholder="Un équipement par ligne&#10;Ex :&#10;Bloc opératoire complet&#10;Centrale à oxygène&#10;Imagerie numérique">{{ old('equipments', $establishment->equipments) }}</textarea>
                    <span class="hint">Un équipement par ligne : ils s'afficheront sous forme de liste.</span>
                </div>
            </div>

            <div>
                <div class="card">
                    <h2>Images</h2>

                    <div class="field">
                        <label for="logo">Logo de l'établissement</label>
                        <div class="media-row">
                            <div class="preview" @if ($establishment->logo) style="background:#fff center/contain no-repeat;background-image:url('{{ media($establishment->logo) }}')" @endif>
                                @unless ($establishment->logo) Aucun logo @endunless
                            </div>
                            <input class="input" style="flex:1;min-width:180px" type="file" id="logo" name="logo" accept="image/*">
                        </div>
                    </div>

                    <div class="field">
                        <label for="image">Photo du site</label>
                        <div class="media-row">
                            <div class="preview" @if ($establishment->image) style="background-image:url('{{ media($establishment->image) }}')" @endif>
                                @unless ($establishment->image) Aucune photo @endunless
                            </div>
                            <input class="input" style="flex:1;min-width:180px" type="file" id="image" name="image" accept="image/*">
                        </div>
                    </div>
                </div>

                <div class="card">
                    <h2>Mise en avant</h2>

                    <div class="field">
                        <label class="check">
                            <input type="checkbox" name="is_flagship" value="1" @checked(old('is_flagship', $establishment->is_flagship))>
                            Réalisation phare (établissement équipé entièrement)
                        </label>
                        <span class="hint">Ces références sont présentées en grand, tout en haut de la page.</span>
                    </div>

                    <div class="field">
                        <label class="check">
                            <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $establishment->is_featured))>
                            Afficher sur la page d'accueil
                        </label>
                    </div>

                    <div class="field">
                        <label class="check">
                            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $establishment->is_active))>
                            Visible sur le site
                        </label>
                    </div>

                    <div class="field">
                        <label for="position">Ordre d'affichage</label>
                        <input class="input" type="number" id="position" name="position"
                               value="{{ old('position', $establishment->position ?? 0) }}" min="0">
                    </div>
                </div>

                <div class="card">
                    <div class="form-actions">
                        <button class="btn btn-primary" type="submit">💾 Enregistrer</button>
                        <a class="btn btn-line" href="{{ route('admin.establishments.index') }}">Annuler</a>
                    </div>
                </div>
            </div>
        </div>
    </form>

@endsection
