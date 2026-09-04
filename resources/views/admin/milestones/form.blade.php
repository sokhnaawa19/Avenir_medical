@extends('admin.layout')

@section('title', $milestone->exists ? 'Modifier l’étape' : 'Nouvelle étape')

@section('content')

    <form method="POST" enctype="multipart/form-data"
          action="{{ $milestone->exists ? route('admin.milestones.update', $milestone) : route('admin.milestones.store') }}">
        @csrf
        @if ($milestone->exists)
            @method('PUT')
        @endif

        <div class="card" style="max-width:720px">
            <div class="grid g2">
                <div class="field">
                    <label for="year">Année <span class="required">*</span></label>
                    <input class="input @error('year') invalid @enderror" type="text" id="year" name="year"
                           value="{{ old('year', $milestone->year) }}" required placeholder="Ex : 2018">
                    @error('year')<span class="err">{{ $message }}</span>@enderror
                </div>

                <div class="field">
                    <label for="position">Ordre d'affichage</label>
                    <input class="input" type="number" id="position" name="position"
                           value="{{ old('position', $milestone->position ?? 0) }}" min="0">
                    <span class="hint">Du plus ancien au plus récent.</span>
                </div>
            </div>

            <div class="field">
                <label for="title">Titre de l'étape <span class="required">*</span></label>
                <input class="input @error('title') invalid @enderror" type="text" id="title" name="title"
                       value="{{ old('title', $milestone->title) }}" required
                       placeholder="Ex : Création de l'entreprise à Dakar">
                @error('title')<span class="err">{{ $message }}</span>@enderror
            </div>

            <div class="field">
                <label for="description">Récit</label>
                <textarea class="input" id="description" name="description"
                          placeholder="Racontez ce qui s'est passé cette année-là.">{{ old('description', $milestone->description) }}</textarea>
            </div>

            <div class="field">
                <label for="image">Photo (facultative)</label>
                <div class="media-row">
                    <div class="preview" @if ($milestone->image) style="background-image:url('{{ media($milestone->image) }}')" @endif>
                        @unless ($milestone->image) Aucune photo @endunless
                    </div>
                    <input class="input" style="flex:1;min-width:200px" type="file" id="image" name="image" accept="image/*">
                </div>
            </div>

            <div class="field">
                <label class="check">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $milestone->is_active))>
                    Étape visible sur le site
                </label>
            </div>

            <div class="form-actions">
                <button class="btn btn-primary" type="submit">💾 Enregistrer</button>
                <a class="btn btn-line" href="{{ route('admin.milestones.index') }}">Annuler</a>
            </div>
        </div>
    </form>

@endsection
