@extends('admin.layout')

@section('title', $partner->exists ? 'Modifier le partenaire' : 'Nouveau partenaire')

@section('content')

    @php
        // Les domaines déjà rattachés à cette marque.
        $links = $partner->exists ? $partner->domains->keyBy('id') : collect();
        $linked = $links->keys();
    @endphp

    <form method="POST" enctype="multipart/form-data"
          action="{{ $partner->exists ? route('admin.partners.update', $partner) : route('admin.partners.store') }}">
        @csrf
        @if ($partner->exists)
            @method('PUT')
        @endif

        <div class="card" style="max-width:720px">
            <div class="field">
                <label for="name">Nom du partenaire <span class="required">*</span></label>
                <input class="input @error('name') invalid @enderror" type="text" id="name" name="name"
                       value="{{ old('name', $partner->name) }}" required placeholder="Ex : Mindray">
                @error('name')<span class="err">{{ $message }}</span>@enderror
            </div>

            <div class="field">
                <label for="logo">Logo</label>
                <div class="media-row">
                    <div class="preview" style="background:#fff;background-size:contain;background-repeat:no-repeat"
                         @if ($partner->logo) style="background-image:url('{{ media($partner->logo) }}');background:#fff center/contain no-repeat" @endif>
                        @unless ($partner->logo) Aucun logo @endunless
                    </div>
                    <input class="input" style="flex:1;min-width:200px" type="file" id="logo" name="logo" accept="image/*">
                </div>
                <span class="hint">PNG avec fond transparent de préférence. Sans logo, les initiales s'affichent.</span>
                @error('logo')<span class="err">{{ $message }}</span>@enderror
            </div>

            <div class="grid g2">
                <div class="field">
                    <label for="country">Pays</label>
                    <input class="input" type="text" id="country" name="country"
                           value="{{ old('country', $partner->country) }}" placeholder="Ex : Chine">
                </div>

                <div class="field">
                    <label for="website">Site internet</label>
                    <input class="input @error('website') invalid @enderror" type="url" id="website" name="website"
                           value="{{ old('website', $partner->website) }}" placeholder="https://…">
                    @error('website')<span class="err">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="field">
                <label for="exclusivity_scope">Portée de l'exclusivité</label>
                <input class="input" type="text" id="exclusivity_scope" name="exclusivity_scope"
                       value="{{ old('exclusivity_scope', $partner->exclusivity_scope) }}"
                       placeholder="Ex : Afrique de l'Ouest, Sénégal, Afrique subsaharienne">
                <span class="hint">S'affiche sur le badge : « Exclusivité Afrique de l'Ouest ». À remplir si le partenariat est exclusif.</span>
            </div>

            <div class="field">
                <label for="description">Description (facultative)</label>
                <textarea class="input" id="description" name="description"
                          placeholder="En quelques mots, ce que ce partenaire apporte.">{{ old('description', $partner->description) }}</textarea>
            </div>

            <div class="grid g2">
                <div class="field">
                    <label for="position">Ordre d'affichage</label>
                    <input class="input" type="number" id="position" name="position"
                           value="{{ old('position', $partner->position ?? 0) }}" min="0">
                </div>

                <div class="field">
                    <label class="check">
                        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $partner->is_active))>
                        Partenaire visible sur le site
                    </label>
                    <label class="check" style="margin-top:10px">
                        <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $partner->is_featured))>
                        Afficher aussi sur la page d'accueil
                    </label>
                    <label class="check" style="margin-top:10px">
                        <input type="checkbox" name="is_exclusive" value="1" @checked(old('is_exclusive', $partner->is_exclusive))>
                        ⭐ Partenariat exclusif
                    </label>
                </div>
            </div>

        </div>

        {{--
            Ce que cette marque fournit, domaine par domaine.
            Les gammes cochées s'affichent sur la page « Nos domaines ».
        --}}
        <details class="card fold" style="max-width:720px" @if ($linked->isNotEmpty() || $errors->has('domains')) open @endif>
            <summary>
                Ce que cette marque fournit <span class="fold-tag">facultatif</span>
            </summary>

            <p class="hint" style="margin:12px 0 4px">
                Cochez les domaines équipés par la marque, puis listez les gammes,
                une par ligne. Elles apparaissent sous le domaine correspondant sur
                la page « Nos domaines ».
            </p>

            <div class="brand-domains">
                @foreach ($domains as $domain)
                    @php
                        $checked = in_array($domain->id, old('domains', $linked->all()));
                        $ranges = old('ranges.'.$domain->id, $links->get($domain->id)?->pivot->ranges ?? '');
                    @endphp

                    <div class="brand-domain">
                        <label class="check">
                            <input type="checkbox" name="domains[]" value="{{ $domain->id }}" @checked($checked)>
                            <span aria-hidden="true">{{ $domain->displayIcon() }}</span> {{ $domain->title }}
                        </label>

                        <textarea class="input" name="ranges[{{ $domain->id }}]" rows="3"
                                  placeholder="Aquilion Lightning | Scanner polyvalent&#10;Vantage Orian | IRM 1,5 T">{{ $ranges }}</textarea>
                    </div>
                @endforeach
            </div>
        </details>

        <div class="card" style="max-width:720px">
            <div class="form-actions" style="margin:0">
                <button class="btn btn-primary" type="submit">💾 Enregistrer</button>
                <a class="btn btn-line" href="{{ route('admin.partners.index') }}">Annuler</a>
            </div>
        </div>
    </form>

@endsection
