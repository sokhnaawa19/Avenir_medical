@extends('admin.layout')

@section('title', $product->exists ? 'Modifier le produit' : 'Nouveau produit')

@section('content')

    <form method="POST" enctype="multipart/form-data"
          action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}">
        @csrf
        @if ($product->exists)
            @method('PUT')
        @endif

        <div class="grid g2" style="align-items:start">
            <div>
                <div class="card">
                    <h2>Informations principales</h2>

                    <div class="field">
                        <label for="name">Nom du produit <span class="required">*</span></label>
                        <input class="input @error('name') invalid @enderror" type="text" id="name" name="name"
                               value="{{ old('name', $product->name) }}" required>
                        @error('name')<span class="err">{{ $message }}</span>@enderror
                    </div>

                    <div class="field">
                        <label for="category_id">Catégorie</label>
                        <select class="input" id="category_id" name="category_id">
                            <option value="">— Aucune —</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid g2">
                        <div class="field">
                            <label for="domain_id">Domaine d'intervention</label>
                            <select class="input" id="domain_id" name="domain_id">
                                <option value="">— Aucun —</option>
                                @foreach ($domains as $domain)
                                    <option value="{{ $domain->id }}" @selected(old('domain_id', $product->domain_id) == $domain->id)>
                                        {{ $domain->title }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="hint">Le produit apparaîtra sur la page de ce domaine.</span>
                        </div>

                        <div class="field">
                            <label for="partner_id">Marque / fabricant</label>
                            <select class="input" id="partner_id" name="partner_id">
                                <option value="">— Aucune —</option>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}" @selected(old('partner_id', $product->partner_id) == $brand->id)>
                                        {{ $brand->name }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="hint">La liste vient de la rubrique « Partenaires ».</span>
                        </div>
                    </div>

                    <div class="field">
                        <label for="short_description">Description courte</label>
                        <input class="input" type="text" id="short_description" name="short_description"
                               value="{{ old('short_description', $product->short_description) }}"
                               placeholder="Une phrase qui résume le produit">
                    </div>

                    <div class="field">
                        <label for="description">Description complète</label>
                        <textarea class="input" id="description" name="description"
                                  placeholder="Caractéristiques, garantie, livraison…">{{ old('description', $product->description) }}</textarea>
                    </div>
                </div>

                <div class="card">
                    <h2>Prix et stock</h2>

                    <div class="grid g2">
                        <div class="field">
                            <label for="price">Prix ({{ setting('currency') }}) <span class="required">*</span></label>
                            <input class="input @error('price') invalid @enderror" type="number" id="price" name="price"
                                   value="{{ old('price', $product->price) }}" min="0" required>
                            @error('price')<span class="err">{{ $message }}</span>@enderror
                        </div>

                        <div class="grid g2">


                            <div class="field">


                                <label for="units_per_box">Unités par carton</label>


                                <input class="input" type="number" id="units_per_box" name="units_per_box"


                                       value="{{ old('units_per_box', $product->units_per_box) }}" min="1" placeholder="Ex : 50">


                                <span class="hint">


                                    Laissez vide si le produit se vend à l'unité.


                                    Sinon, le site affiche le prix du carton (prix unitaire × quantité).


                                </span>


                            </div>


                        


                            <div class="field">


                                <label for="box_label">Nom du conditionnement</label>


                                <input class="input" type="text" id="box_label" name="box_label"


                                       value="{{ old('box_label', $product->box_label) }}" placeholder="Carton" maxlength="40">


                                <span class="hint">« Carton », « Boîte », « Sachet »… « Carton » par défaut.</span>


                            </div>


                        </div>


                        <div class="field">
                            <label for="old_price">Ancien prix (promotion)</label>
                            <input class="input @error('old_price') invalid @enderror" type="number" id="old_price"
                                   name="old_price" value="{{ old('old_price', $product->old_price) }}" min="0">
                            <span class="hint">Laissez vide s'il n'y a pas de promotion.</span>
                            @error('old_price')<span class="err">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="grid g2">
                        <div class="field">
                            <label for="stock">Stock disponible</label>
                            <input class="input" type="number" id="stock" name="stock" value="{{ old('stock', $product->stock) }}" min="0">
                            <span class="hint">Laissez vide pour ne pas suivre le stock.</span>
                        </div>

                        <div class="field">
                            <label for="reference">Référence</label>
                            <input class="input" type="text" id="reference" name="reference" value="{{ old('reference', $product->reference) }}">
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <div class="card">
                    <h2>Photo du produit</h2>

                    <div class="media-row">
                        <div class="preview" @if ($product->image) style="background-image:url('{{ media($product->image) }}')" @endif>
                            @unless ($product->image)Aucune photo @endunless
                        </div>

                        <div style="flex:1;min-width:180px">
                            <input class="input" type="file" name="image" accept="image/*">
                            <span class="hint">JPG, PNG ou WEBP — 4 Mo maximum.</span>

                            <div class="field" style="margin-top:14px">
                                <label for="emoji">Icône de remplacement</label>
                                <input class="input" type="text" id="emoji" name="emoji"
                                       value="{{ old('emoji', $product->emoji) }}" maxlength="8" placeholder="📦">
                                <span class="hint">Affichée si aucune photo n'est fournie.</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <h2>Affichage</h2>

                    <div class="field">
                        <label class="check">
                            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->is_active))>
                            Produit visible dans la boutique
                        </label>
                    </div>

                    <div class="field">
                        <label class="check">
                            <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $product->is_featured))>
                            Mettre en avant sur la page d'accueil
                        </label>
                    </div>


                    @include('admin.partials.video-field', ['model' => $product])

                    <div class="field">
                        <label for="position">Ordre d'affichage</label>
                        <input class="input" type="number" id="position" name="position"
                               value="{{ old('position', $product->position ?? 0) }}" min="0">
                        <span class="hint">Les plus petits nombres apparaissent en premier.</span>
                    </div>
                </div>

                <div class="card">
                    <div class="form-actions">
                        <button class="btn btn-primary" type="submit">💾 Enregistrer</button>
                        <a class="btn btn-line" href="{{ route('admin.products.index') }}">Annuler</a>
                    </div>
                </div>
            </div>
        </div>
    </form>

@endsection
