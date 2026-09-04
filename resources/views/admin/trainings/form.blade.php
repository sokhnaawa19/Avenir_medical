@extends('admin.layout')

@section('title', $training->exists ? 'Modifier la formation' : 'Nouvelle formation')

@section('content')

    <form method="POST" enctype="multipart/form-data"
          action="{{ $training->exists ? route('admin.trainings.update', $training) : route('admin.trainings.store') }}">
        @csrf
        @if ($training->exists)
            @method('PUT')
        @endif

        <div class="card" style="max-width:760px">
            <div class="field">
                <label for="title">Intitulé de la formation <span class="required">*</span></label>
                <input class="input @error('title') invalid @enderror" type="text" id="title" name="title"
                       value="{{ old('title', $training->title) }}" required
                       placeholder="Ex : Maintenance des échographes — niveau avancé">
                @error('title')<span class="err">{{ $message }}</span>@enderror
            </div>

            <div class="grid g2">
                <div class="field">
                    <label for="organism">Organisme formateur</label>
                    <input class="input" type="text" id="organism" name="organism"
                           value="{{ old('organism', $training->organism) }}" placeholder="Ex : Mindray Academy">
                    <span class="hint">Le fabricant ou l'institut qui a assuré la formation.</span>
                </div>

                <div class="field">
                    <label for="year">Année</label>
                    <input class="input" type="text" id="year" name="year" value="{{ old('year', $training->year) }}">
                </div>
            </div>

            <div class="grid g2">
                <div class="field">
                    <label for="city">Ville</label>
                    <input class="input" type="text" id="city" name="city"
                           value="{{ old('city', $training->city) }}" placeholder="Ex : Shenzhen">
                </div>

                <div class="field">
                    <label for="country">Pays</label>
                    <input class="input" type="text" id="country" name="country"
                           value="{{ old('country', $training->country) }}" placeholder="Ex : Chine">
                </div>
            </div>

            <div class="grid g2">
                <div class="field">
                    <label for="participants">Nombre de techniciens formés</label>
                    <input class="input" type="number" id="participants" name="participants"
                           value="{{ old('participants', $training->participants) }}" min="0">
                </div>

                <div class="field">
                    <label for="position">Ordre d'affichage</label>
                    <input class="input" type="number" id="position" name="position"
                           value="{{ old('position', $training->position ?? 0) }}" min="0">
                </div>
            </div>

            <div class="field">
                <label for="description">Description</label>
                <textarea class="input" id="description" name="description"
                          placeholder="Ce qui a été appris et ce que cela apporte à vos clients.">{{ old('description', $training->description) }}</textarea>
            </div>

            <div class="field">
                <label for="image">Photo</label>
                <div class="media-row">
                    <div class="preview" @if ($training->image) style="background-image:url('{{ media($training->image) }}')" @endif>
                        @unless ($training->image) Aucune photo @endunless
                    </div>
                    <input class="input" style="flex:1;min-width:200px" type="file" id="image" name="image" accept="image/*">
                </div>
            </div>

            @include('admin.partials.photos-field', [
                'model' => $training,
                'route' => 'admin.trainings.photos.destroy',
                'label' => 'Photos de la formation',
                'hint' => "Les techniciens sur place, les manipulations, la remise des certificats… Jusqu'à 12 photos.",
            ])

            <div class="field">
                <label class="check">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $training->is_active))>
                    Formation visible sur le site
                </label>
            </div>

            <div class="form-actions">
                <button class="btn btn-primary" type="submit">💾 Enregistrer</button>
                <a class="btn btn-line" href="{{ route('admin.trainings.index') }}">Annuler</a>
            </div>
        </div>
    </form>

@endsection
