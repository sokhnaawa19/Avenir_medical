@extends('admin.layout')

@section('title', $service->exists ? 'Modifier le service' : 'Nouveau service')

@section('content')

    <form method="POST" enctype="multipart/form-data"
          action="{{ $service->exists ? route('admin.services.update', $service) : route('admin.services.store') }}">
        @csrf
        @if ($service->exists)
            @method('PUT')
        @endif

        <div class="card" style="max-width:760px">
            <div class="grid g2">
                <div class="field">
                    <label for="title">Titre <span class="required">*</span></label>
                    <input class="input @error('title') invalid @enderror" type="text" id="title" name="title"
                           value="{{ old('title', $service->title) }}" required>
                    @error('title')<span class="err">{{ $message }}</span>@enderror
                </div>

                <div class="field">
                    <label for="icon">Icône</label>
                    <input class="input" type="text" id="icon" name="icon" value="{{ old('icon', $service->icon) }}"
                           maxlength="8" placeholder="🛠️">
                    <span class="hint">Un emoji suffit.</span>
                </div>
            </div>

            <div class="field">
                <label for="description">Description</label>
                <textarea class="input" id="description" name="description">{{ old('description', $service->description) }}</textarea>
            </div>

            <div class="field">
                <label for="image">Photo (facultative)</label>
                <div class="media-row">
                    <div class="preview" @if ($service->image) style="background-image:url('{{ media($service->image) }}')" @endif>
                        @unless ($service->image)Aucune photo @endunless
                    </div>
                    <input class="input" style="flex:1;min-width:200px" type="file" id="image" name="image" accept="image/*">
                </div>
            </div>

            <div class="grid g2">
                <div class="field">
                    <label for="position">Ordre d'affichage</label>
                    <input class="input" type="number" id="position" name="position"
                           value="{{ old('position', $service->position ?? 0) }}" min="0">
                </div>

                <div class="field">
                    <label class="check" style="margin-top:26px">
                        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $service->is_active))>
                        Service visible sur le site
                    </label>
                </div>
            </div>

            @include('admin.partials.photos-field', [
                'model' => $service,
                'route' => 'admin.services.photos.destroy',
                'label' => 'Photos du service',
                'hint' => "Vos réalisations : installations, mises en service, équipes sur le terrain.",
            ])

            <div class="form-actions">
                <button class="btn btn-primary" type="submit">💾 Enregistrer</button>
                <a class="btn btn-line" href="{{ route('admin.services.index') }}">Annuler</a>
            </div>
        </div>
    </form>

@endsection
