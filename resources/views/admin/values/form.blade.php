@extends('admin.layout')

@section('title', $value->exists ? 'Modifier la valeur' : 'Nouvelle valeur')

@section('content')

    <form method="POST" action="{{ $value->exists ? route('admin.values.update', $value) : route('admin.values.store') }}">
        @csrf
        @if ($value->exists)
            @method('PUT')
        @endif

        <div class="card" style="max-width:640px">
            <div class="grid g2">
                <div class="field">
                    <label for="title">Titre <span class="required">*</span></label>
                    <input class="input @error('title') invalid @enderror" type="text" id="title" name="title"
                           value="{{ old('title', $value->title) }}" required>
                    @error('title')<span class="err">{{ $message }}</span>@enderror
                </div>

                <div class="field">
                    <label for="icon">Icône</label>
                    <input class="input" type="text" id="icon" name="icon" value="{{ old('icon', $value->icon) }}" maxlength="8">
                </div>
            </div>

            <div class="field">
                <label for="description">Description</label>
                <textarea class="input" id="description" name="description">{{ old('description', $value->description) }}</textarea>
            </div>

            <div class="grid g2">
                <div class="field">
                    <label for="position">Ordre d'affichage</label>
                    <input class="input" type="number" id="position" name="position"
                           value="{{ old('position', $value->position ?? 0) }}" min="0">
                </div>

                <div class="field">
                    <label class="check" style="margin-top:26px">
                        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $value->is_active))>
                        Valeur visible sur le site
                    </label>
                </div>
            </div>

            <div class="form-actions">
                <button class="btn btn-primary" type="submit">💾 Enregistrer</button>
                <a class="btn btn-line" href="{{ route('admin.values.index') }}">Annuler</a>
            </div>
        </div>
    </form>

@endsection
