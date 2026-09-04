@extends('admin.layout')

@section('title', $agency->exists ? "Modifier l'agence" : 'Nouvelle agence')

@section('content')

    <form method="POST" enctype="multipart/form-data"
          action="{{ $agency->exists ? route('admin.agencies.update', $agency) : route('admin.agencies.store') }}">
        @csrf
        @if ($agency->exists)
            @method('PUT')
        @endif

        <div class="card" style="max-width:760px">
            <div class="field">
                <label for="name">Nom de l'agence <span class="required">*</span></label>
                <input class="input @error('name') invalid @enderror" type="text" id="name" name="name"
                       value="{{ old('name', $agency->name) }}" required placeholder="Ex : Agence de Thiès">
                @error('name')<span class="err">{{ $message }}</span>@enderror
            </div>

            <div class="grid g2">
                <div class="field">
                    <label for="region">Région</label>
                    <input class="input" type="text" id="region" name="region" list="regions"
                           value="{{ old('region', $agency->region) }}" placeholder="Ex : Thiès">
                    <datalist id="regions">
                        <option value="Dakar"></option><option value="Thiès"></option>
                        <option value="Saint-Louis"></option><option value="Ziguinchor"></option>
                        <option value="Kaolack"></option><option value="Diourbel"></option>
                        <option value="Tambacounda"></option><option value="Louga"></option>
                    </datalist>
                </div>

                <div class="field">
                    <label for="country">Pays</label>
                    <input class="input" type="text" id="country" name="country"
                           value="{{ old('country', $agency->country ?: 'Sénégal') }}"
                           placeholder="Ex : Mali, Guinée, Côte d'Ivoire…">
                    <span class="hint">Pour montrer votre développement dans la sous-région.</span>
                </div>
            </div>

            <div class="grid g2">
                <div class="field">
                    <label for="status">Situation <span class="required">*</span></label>
                    <select class="input" id="status" name="status" required>
                        @foreach (\App\Models\Agency::statuses() as $cle => $libelle)
                            <option value="{{ $cle }}" @selected(old('status', $agency->status) === $cle)>{{ $libelle }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="field">
                    <label for="opening_year">Année d'ouverture (réelle ou prévue)</label>
                    <input class="input" type="text" id="opening_year" name="opening_year"
                           value="{{ old('opening_year', $agency->opening_year) }}" placeholder="Ex : 2027">
                </div>
            </div>

            <div class="grid g2">
                <div class="field">
                    <label for="address">Adresse</label>
                    <input class="input" type="text" id="address" name="address" value="{{ old('address', $agency->address) }}">
                </div>

                <div class="field">
                    <label for="phone">Téléphone</label>
                    <input class="input" type="tel" id="phone" name="phone" value="{{ old('phone', $agency->phone) }}">
                </div>
            </div>

            <div class="field">
                <label for="description">Description</label>
                <textarea class="input" id="description" name="description"
                          placeholder="Ce que cette agence apporte : proximité, délais d'intervention…">{{ old('description', $agency->description) }}</textarea>
            </div>

            <div class="field">
                <label for="image">Photo</label>
                <div class="media-row">
                    <div class="preview" @if ($agency->image) style="background-image:url('{{ media($agency->image) }}')" @endif>
                        @unless ($agency->image) Aucune photo @endunless
                    </div>
                    <input class="input" style="flex:1;min-width:200px" type="file" id="image" name="image" accept="image/*">
                </div>
            </div>

            <div class="grid g2">
                <div class="field">
                    <label for="position">Ordre d'affichage</label>
                    <input class="input" type="number" id="position" name="position"
                           value="{{ old('position', $agency->position ?? 0) }}" min="0">
                </div>

                <div class="field">
                    <label class="check" style="margin-top:26px">
                        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $agency->is_active))>
                        Agence visible sur le site
                    </label>
                </div>
            </div>

            <div class="form-actions">
                <button class="btn btn-primary" type="submit">💾 Enregistrer</button>
                <a class="btn btn-line" href="{{ route('admin.agencies.index') }}">Annuler</a>
            </div>
        </div>
    </form>

@endsection
