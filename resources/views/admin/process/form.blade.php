@extends('admin.layout')

@section('title', $step->exists ? "Modifier l'étape" : 'Nouvelle étape du parcours')

@section('content')

    <form method="POST" enctype="multipart/form-data"
          action="{{ $step->exists ? route('admin.process.update', $step) : route('admin.process.store') }}">
        @csrf
        @if ($step->exists)
            @method('PUT')
        @endif

        <div class="card" style="max-width:720px">
            <div class="grid g2">
                <div class="field">
                    <label for="title">Nom de l'étape <span class="required">*</span></label>
                    <input class="input @error('title') invalid @enderror" type="text" id="title" name="title"
                           value="{{ old('title', $step->title) }}" required placeholder="Ex : Comprendre">
                    @error('title')<span class="err">{{ $message }}</span>@enderror
                </div>

                <div class="field">
                    <label for="icon">Icône</label>
                    <input class="input" type="text" id="icon" name="icon" value="{{ old('icon', $step->icon) }}"
                           maxlength="8" placeholder="🔍">
                </div>
            </div>

            <div class="field">
                <label for="subtitle">Phrase courte</label>
                <input class="input" type="text" id="subtitle" name="subtitle"
                       value="{{ old('subtitle', $step->subtitle) }}"
                       placeholder="Ex : Nous analysons votre besoin">
            </div>

            <div class="field">
                <label for="description">Détail</label>
                <textarea class="input" id="description" name="description"
                          placeholder="Ex : Vos contraintes, votre environnement et vos objectifs.">{{ old('description', $step->description) }}</textarea>
            </div>

            <div class="field">
                <label for="image">Photo (facultative)</label>
                <div class="media-row">
                    <div class="preview" @if ($step->image) style="background-image:url('{{ media($step->image) }}')" @endif>
                        @unless ($step->image) Aucune photo @endunless
                    </div>
                    <input class="input" style="flex:1;min-width:200px" type="file" id="image" name="image" accept="image/*">
                </div>
            </div>

            <div class="grid g2">
                <div class="field">
                    <label for="position">Ordre dans le parcours</label>
                    <input class="input" type="number" id="position" name="position"
                           value="{{ old('position', $step->position ?? 0) }}" min="0">
                    <span class="hint">0 pour la première étape, 1 pour la deuxième…</span>
                </div>

                <div class="field">
                    <label class="check" style="margin-top:26px">
                        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $step->is_active))>
                        Étape visible sur le site
                    </label>
                </div>
            </div>

            <div class="form-actions">
                <button class="btn btn-primary" type="submit">💾 Enregistrer</button>
                <a class="btn btn-line" href="{{ route('admin.process.index') }}">Annuler</a>
            </div>
        </div>
    </form>

@endsection
