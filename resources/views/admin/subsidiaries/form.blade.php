@extends('admin.layout')

@section('title', $subsidiary->exists ? "Modifier l'entreprise" : 'Nouvelle entreprise du groupe')

@section('content')

    <form method="POST" enctype="multipart/form-data"
          action="{{ $subsidiary->exists ? route('admin.subsidiaries.update', $subsidiary) : route('admin.subsidiaries.store') }}">
        @csrf
        @if ($subsidiary->exists)
            @method('PUT')
        @endif

        <div class="grid g2" style="align-items:start">
            <div class="card">
                <h2>L'entreprise</h2>

                <div class="field">
                    <label for="name">Nom <span class="required">*</span></label>
                    <input class="input @error('name') invalid @enderror" type="text" id="name" name="name"
                           value="{{ old('name', $subsidiary->name) }}" required placeholder="Ex : AVENIR PHARMA">
                    @error('name')<span class="err">{{ $message }}</span>@enderror
                </div>

                <div class="field">
                    <label for="tagline">Signature</label>
                    <input class="input" type="text" id="tagline" name="tagline"
                           value="{{ old('tagline', $subsidiary->tagline) }}"
                           placeholder="Ex : Distribution pharmaceutique">
                </div>

                <div class="grid g2">
                    <div class="field">
                        <label for="activity">Activité</label>
                        <input class="input" type="text" id="activity" name="activity"
                               value="{{ old('activity', $subsidiary->activity) }}"
                               placeholder="Ex : Consommables médicaux">
                    </div>

                    <div class="field">
                        <label for="founded_year">Année de création</label>
                        <input class="input" type="text" id="founded_year" name="founded_year"
                               value="{{ old('founded_year', $subsidiary->founded_year) }}">
                    </div>
                </div>

                <div class="field">
                    <label for="description">Description</label>
                    <textarea class="input" id="description" name="description"
                              placeholder="Ce que fait cette entreprise et comment elle complète AVENIR MEDICAL.">{{ old('description', $subsidiary->description) }}</textarea>
                </div>

                <div class="grid g2">
                    <div class="field">
                        <label for="website">Site internet</label>
                        <input class="input @error('website') invalid @enderror" type="url" id="website" name="website"
                               value="{{ old('website', $subsidiary->website) }}" placeholder="https://…">
                        @error('website')<span class="err">{{ $message }}</span>@enderror
                    </div>

                    <div class="field">
                        <label for="color">Couleur de la marque</label>
                        <input class="input" type="color" id="color" name="color"
                               value="{{ old('color', $subsidiary->color ?: '#16657F') }}">
                        <span class="hint">Utilisée pour la carte de cette entreprise.</span>
                    </div>
                </div>
            </div>

            <div>
                <div class="card">
                    <h2>Images</h2>

                    <div class="field">
                        <label for="logo">Logo</label>
                        <div class="media-row">
                            <div class="preview" @if ($subsidiary->logo) style="background:#fff center/contain no-repeat;background-image:url('{{ media($subsidiary->logo) }}')" @endif>
                                @unless ($subsidiary->logo) Aucun logo @endunless
                            </div>
                            <input class="input" style="flex:1;min-width:180px" type="file" id="logo" name="logo" accept="image/*">
                        </div>
                    </div>

                    <div class="field">
                        <label for="image">Photo d'illustration</label>
                        <div class="media-row">
                            <div class="preview" @if ($subsidiary->image) style="background-image:url('{{ media($subsidiary->image) }}')" @endif>
                                @unless ($subsidiary->image) Aucune photo @endunless
                            </div>
                            <input class="input" style="flex:1;min-width:180px" type="file" id="image" name="image" accept="image/*">
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="grid g2">
                        <div class="field">
                            <label for="position">Ordre</label>
                            <input class="input" type="number" id="position" name="position"
                                   value="{{ old('position', $subsidiary->position ?? 0) }}" min="0">
                        </div>

                        <div class="field">
                            <label class="check" style="margin-top:26px">
                                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $subsidiary->is_active))>
                                Visible sur le site
                            </label>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button class="btn btn-primary" type="submit">💾 Enregistrer</button>
                        <a class="btn btn-line" href="{{ route('admin.subsidiaries.index') }}">Annuler</a>
                    </div>
                </div>
            </div>
        </div>
    </form>

@endsection
