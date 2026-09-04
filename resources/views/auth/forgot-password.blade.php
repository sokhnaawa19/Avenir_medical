@extends('layouts.public')

@section('title', 'Mot de passe oublié — '.setting('site_name'))

@section('content')

    @include('partials.page-hero', ['title' => __('site.mot_de_passe_oublie'), 'crumb' => __('site.mon_compte')])

    <section>
        <div class="wrap" style="max-width:520px">
            @include('partials.flash')

            <div class="card">
                <p class="text-muted">{{ __('site.indiquez_votre_email_nous_vous_envoyons_un_l') }}</p>

                <form method="POST" action="{{ route('password.email') }}" class="mt-2">
                    @csrf

                    <div class="field">
                        <label for="email">Email</label>
                        <input class="input @error('email') is-invalid @enderror" type="email" id="email" name="email"
                               value="{{ old('email') }}" required autofocus>
                        @error('email')<span class="error-text">{{ $message }}</span>@enderror
                    </div>

                    <button class="btn btn-primary btn-block" type="submit">{{ __('site.envoyer_le_lien') }}</button>
                </form>

                <p class="center mt-3" style="font-size:.9rem">
                    <a class="link" href="{{ route('login') }}">{{ __('site.retour_a_la_connexion') }}</a>
                </p>
            </div>
        </div>
    </section>

@endsection
