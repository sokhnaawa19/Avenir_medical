@extends('admin.layout')

@section('title', $category->exists ? 'Modifier la catégorie' : 'Nouvelle catégorie')

@section('content')

    <form method="POST" enctype="multipart/form-data"
          action="{{ $category->exists ? route('admin.categories.update', $category) : route('admin.categories.store') }}">
        @csrf
        @if ($category->exists)
            @method('PUT')
        @endif

        <div class="card" style="max-width:720px">
            <div class="field">
                <label for="name">Nom de la catégorie <span class="required">*</span></label>
                <input class="input @error('name') invalid @enderror" type="text" id="name" name="name"
                       value="{{ old('name', $category->name) }}" required>
                @error('name')<span class="err">{{ $message }}</span>@enderror
            </div>

            <div class="field">
                <label for="description">Description</label>
                <textarea class="input" id="description" name="description">{{ old('description', $category->description) }}</textarea>
            </div>

            <div class="field">
                <label for="image">Image</label>
                <div class="media-row">
                    <div class="preview" @if ($category->image) style="background-image:url('{{ media($category->image) }}')" @endif>
                        @unless ($category->image)Aucune image @endunless
                    </div>
                    <input class="input" style="flex:1;min-width:200px" type="file" id="image" name="image" accept="image/*">
                </div>
            </div>

            <div class="grid g2">
                <div class="field">
                    <label for="position">Ordre d'affichage</label>
                    <input class="input" type="number" id="position" name="position"
                           value="{{ old('position', $category->position ?? 0) }}" min="0">
                </div>

                <div class="field">
                    <label class="check" style="margin-top:26px">
                        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $category->is_active))>
                        Catégorie visible
                    </label>
                </div>
            </div>

            <div class="form-actions">
                <button class="btn btn-primary" type="submit">💾 Enregistrer</button>
                <a class="btn btn-line" href="{{ route('admin.categories.index') }}">Annuler</a>
            </div>
        </div>
    </form>

@endsection
