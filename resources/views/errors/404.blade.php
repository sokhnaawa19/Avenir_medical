@extends('layouts.public')

@section('title', 'Page introuvable — '.setting('site_name'))

@section('content')
    @include('partials.page-hero', ['title' => 'Cette page n’existe pas'])

    <section>
        <div class="wrap vide">
            <span>@include('partials.icon', ['name' => 'search', 'size' => 64])</span>
            <p>{{ __('site.le_lien_que_vous_avez_suivi_ne_mene_a_aucune') }}</p>
            <a class="btn btn-primary mt-3" href="{{ lroute('home') }}">{{ __('site.retour_a_laccueil') }}</a>
        </div>
    </section>
@endsection
