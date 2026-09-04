@extends('layouts.public')

@section('title', 'Accès refusé — '.setting('site_name'))

@section('content')
    @include('partials.page-hero', ['title' => 'Accès refusé'])

    <section>
        <div class="wrap vide">
            <span>@include('partials.icon', ['name' => 'lock', 'size' => 64])</span>
            <p>{{ __('site.vous_navez_pas_les_droits_necessaires_pour_v') }}</p>
            <a class="btn btn-primary mt-3" href="{{ lroute('home') }}">{{ __('site.retour_a_laccueil') }}</a>
        </div>
    </section>
@endsection
