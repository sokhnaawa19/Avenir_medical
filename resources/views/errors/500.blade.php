@extends('layouts.public')

@section('title', 'Une erreur est survenue')

@section('content')
    @include('partials.page-hero', ['title' => 'Une erreur est survenue'])

    <section>
        <div class="wrap vide">
            <span>@include('partials.icon', ['name' => 'settings', 'size' => 64])</span>
            <p>{{ __('site.le_site_rencontre_un_probleme_temporaire_mer') }}</p>
            <a class="btn btn-primary mt-3" href="{{ lroute('home') }}">{{ __('site.retour_a_laccueil') }}</a>
        </div>
    </section>
@endsection
