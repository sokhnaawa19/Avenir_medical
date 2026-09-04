@extends('admin.layout')

@section('title', $domain->exists ? 'Modifier le domaine' : 'Nouveau domaine')

@section('content')

    @php use App\Support\LineList; @endphp

    <form method="POST" enctype="multipart/form-data"
          action="{{ $domain->exists ? route('admin.domains.update', $domain) : route('admin.domains.store') }}">
        @csrf
        @if ($domain->exists)
            @method('PUT')
        @endif

        {{-- L'essentiel : c'est tout ce qui est obligatoire --}}
        <div class="card" style="max-width:860px">
            <div class="grid g2">
                <div class="field">
                    <label for="title">Titre <span class="required">*</span></label>
                    <input class="input @error('title') invalid @enderror" type="text" id="title" name="title"
                           value="{{ old('title', $domain->title) }}" required>
                    @error('title')<span class="err">{{ $message }}</span>@enderror
                </div>

                <div class="field">
                    <label for="icon">Icône</label>
                    <input class="input" type="text" id="icon" name="icon" maxlength="16"
                           value="{{ old('icon', $domain->icon) }}" placeholder="🩻">
                    <span class="hint">Un emoji : 🚑 🩻 🔬 🫁 🛏️ 🧤 ❄️ 💧</span>
                </div>
            </div>

            <div class="field">
                <label for="subtitle">Sous-titre</label>
                <input class="input" type="text" id="subtitle" name="subtitle"
                       value="{{ old('subtitle', $domain->subtitle) }}"
                       placeholder="Ex : Radiologie, échographie, monitoring">
            </div>

            <div class="field">
                <label for="intro">Accroche</label>
                <textarea class="input" id="intro" name="intro" rows="2"
                          placeholder="Une phrase qui résume le domaine.">{{ old('intro', $domain->intro) }}</textarea>
                <span class="hint">Affichée en gros en haut de la page, et reprise par Google.</span>
            </div>

            <div class="field">
                <label for="description">Présentation</label>
                <textarea class="input" id="description" name="description" rows="6">{{ old('description', $domain->description) }}</textarea>
            </div>

            <div class="field">
                <label for="image">Photo</label>
                <div class="media-row">
                    <div class="preview" @if ($domain->image) style="background-image:url('{{ media($domain->image) }}')" @endif>
                        @unless ($domain->image)Aucune photo @endunless
                    </div>
                    <input class="input" style="flex:1;min-width:200px" type="file" id="image" name="image" accept="image/*">
                </div>
            </div>

            @include('admin.partials.video-field', ['model' => $domain])

            <div class="grid g2">
                <div class="field">
                    <label for="position">Ordre d'affichage</label>
                    <input class="input" type="number" id="position" name="position"
                           value="{{ old('position', $domain->position ?? 0) }}" min="0">
                </div>

                <div class="field">
                    <label class="check">
                        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $domain->is_active))>
                        Visible sur le site
                    </label>
                    <label class="check" style="margin-top:10px">
                        <input type="checkbox" name="in_gallery" value="1" @checked(old('in_gallery', $domain->in_gallery))>
                        Afficher dans la galerie
                    </label>
                </div>
            </div>
        </div>

        {{--
            Le reste est replié : on ne le voit que si on le cherche.
            Un domaine reste parfaitement présentable sans y toucher.
        --}}
        <details class="card fold" style="max-width:860px" @error('equipments') open @enderror>
            <summary>
                Les équipements de ce domaine <span class="fold-tag">facultatif</span>
            </summary>

            <div class="field" style="margin-top:16px">
                <textarea class="input" id="equipments" name="equipments" rows="8"
                          placeholder="Table d'opération&#10;Scialytique LED&#10;Bistouri électrique">{{ old('equipments', LineList::toText($domain->equipments)) }}</textarea>
                <span class="hint">
                    Une ligne par équipement, c'est tout. Les six premiers s'affichent
                    en étiquettes sur la carte du domaine, le reste est compté
                    (« + 3 autres »). Laissez vide si vous préférez : les étiquettes
                    disparaissent simplement.
                </span>
            </div>
        </details>

        <div class="card" style="max-width:860px">
            <div class="form-actions" style="margin:0">
                <button class="btn btn-primary" type="submit">💾 Enregistrer</button>
                <a class="btn btn-line" href="{{ route('admin.domains.index') }}">Annuler</a>
            </div>
        </div>
    </form>

@endsection
