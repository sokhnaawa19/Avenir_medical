@extends('admin.layout')

@section('title', $post->exists ? 'Modifier l’article' : 'Nouvel article')

@section('content')

    <form method="POST" enctype="multipart/form-data"
          action="{{ $post->exists ? route('admin.posts.update', $post) : route('admin.posts.store') }}">
        @csrf
        @if ($post->exists)
            @method('PUT')
        @endif

        <div class="grid g2" style="align-items:start">
            <div class="card">
                <h2>Contenu de l'article</h2>

                <div class="field">
                    <label for="title">Titre <span class="required">*</span></label>
                    <input class="input @error('title') invalid @enderror" type="text" id="title" name="title"
                           value="{{ old('title', $post->title) }}" required>
                    @error('title')<span class="err">{{ $message }}</span>@enderror
                </div>

                <div class="field">
                    <label for="excerpt">Résumé</label>
                    <input class="input" type="text" id="excerpt" name="excerpt" value="{{ old('excerpt', $post->excerpt) }}"
                           placeholder="Une phrase affichée sur la carte de l'article">
                </div>

                <div class="field">
                    <label for="content">Texte de l'article <span class="required">*</span></label>
                    <textarea class="input @error('content') invalid @enderror" id="content" name="content"
                              style="min-height:320px" required>{{ old('content', $post->content) }}</textarea>
                    <span class="hint">Laissez une ligne vide entre deux paragraphes.</span>
                    @error('content')<span class="err">{{ $message }}</span>@enderror
                </div>
            </div>

            <div>
                <div class="card">
                    <h2>Photo de l'article</h2>

                    <div class="media-row">
                        <div class="preview" @if ($post->image) style="background-image:url('{{ media($post->image) }}')" @endif>
                            @unless ($post->image)Aucune photo @endunless
                        </div>
                        <input class="input" style="flex:1;min-width:180px" type="file" name="image" accept="image/*">
                    </div>
                </div>

                <div class="card">
                    <h2>Publication</h2>


                    @include('admin.partials.video-field', ['model' => $post])

                    <div class="field">
                        <label for="category">Catégorie</label>
                        <input class="input" type="text" id="category" name="category" list="categories-blog"
                               value="{{ old('category', $post->category) }}" placeholder="Ex : Partenariat">
                        <datalist id="categories-blog">
                            <option value="Partenariat"></option>
                            <option value="Actualité"></option>
                            <option value="Projet"></option>
                            <option value="Conseil"></option>
                        </datalist>
                    </div>

                    <div class="field">
                        <label for="published_at">Date de publication</label>
                        <input class="input" type="date" id="published_at" name="published_at"
                               value="{{ old('published_at', optional($post->published_at)->format('Y-m-d')) }}">
                    </div>

                    <div class="field">
                        <label class="check">
                            <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $post->is_published))>
                            Publier l'article sur le site
                        </label>
                    </div>

                    <div class="form-actions">
                        <button class="btn btn-primary" type="submit">💾 Enregistrer</button>
                        <a class="btn btn-line" href="{{ route('admin.posts.index') }}">Annuler</a>
                    </div>
                </div>
            </div>
        </div>
    </form>

@endsection
