@extends('layouts.public')

@section('title', 'Connexion — '.setting('site_name'))

@section('content')

    @include('partials.page-hero', ['title' => 'Se connecter', 'crumb' => 'Mon compte'])

    <section>
        <div class="wrap" style="max-width:520px">
            @include('partials.flash')

            <div class="card">
                <h3>@include('partials.icon', ['name' => 'wave']) Content de vous revoir !</h3>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="field">
                        <label for="email">Email</label>
                        <input class="input @error('email') is-invalid @enderror" type="email" id="email" name="email"
                               value="{{ old('email') }}" required autofocus autocomplete="email">
                        @error('email')<span class="error-text">{{ $message }}</span>@enderror
                    </div>

                    <div class="field">
                        <label for="password">{{ __('site.mot_de_passe') }}</label>
                        <input class="input @error('password') is-invalid @enderror" type="password" id="password"
                               name="password" required autocomplete="current-password">
                        @error('password')<span class="error-text">{{ $message }}</span>@enderror
                    </div>

                    <div class="field">
                        <label style="font-weight:400;font-size:.9rem">
                            <input type="checkbox" name="remember" value="1"> {{ __('site.se_souvenir_de_moi') }}
                        </label>
                    </div>

                    <button class="btn btn-primary btn-block" type="submit">{{ __('site.se_connecter') }}</button>
                </form>

                <p class="center mt-3" style="font-size:.9rem">
                    <a class="link" href="{{ route('password.request') }}">{{ __('site.mot_de_passe_oublie') }}</a><br>
                    {{ __('site.pas_encore_de_compte') }} <a class="link" href="{{ route('register') }}">{{ __('site.creer_un_compte') }}</a>
                </p>
            </div>
        </div>
    </section>

@endsection
