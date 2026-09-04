@extends('layouts.public')

@section('title', 'Créer un compte — '.setting('site_name'))

@section('content')

    @include('partials.page-hero', ['title' => 'Créer mon compte', 'crumb' => 'Mon compte'])

    <section>
        <div class="wrap" style="max-width:560px">
            @include('partials.flash')

            <div class="card">
                <h3>@include('partials.icon', ['name' => 'sparkle']) Quelques informations et c’est prêt</h3>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="field">
                        <label for="name">{{ __('site.prenom_et_nom') }} <span class="required">*</span></label>
                        <input class="input @error('name') is-invalid @enderror" type="text" id="name" name="name"
                               value="{{ old('name') }}" required autofocus>
                        @error('name')<span class="error-text">{{ $message }}</span>@enderror
                    </div>

                    <div class="field">
                        <label for="phone">{{ __('site.telephone') }}</label>
                        <input class="input @error('phone') is-invalid @enderror" type="tel" id="phone" name="phone"
                               value="{{ old('phone') }}" placeholder="{{ __('site.ex_77_123_45_67') }}">
                        @error('phone')<span class="error-text">{{ $message }}</span>@enderror
                    </div>

                    <div class="field">
                        <label for="email">Email <span class="required">*</span></label>
                        <input class="input @error('email') is-invalid @enderror" type="email" id="email" name="email"
                               value="{{ old('email') }}" required autocomplete="email">
                        @error('email')<span class="error-text">{{ $message }}</span>@enderror
                    </div>

                    <div class="field">
                        <label for="password">{{ __('site.mot_de_passe') }} <span class="required">*</span></label>
                        <input class="input @error('password') is-invalid @enderror" type="password" id="password"
                               name="password" required autocomplete="new-password">
                        <span class="form-hint">{{ __('site.8_caracteres_minimum') }}</span>
                        @error('password')<span class="error-text">{{ $message }}</span>@enderror
                    </div>

                    <div class="field">
                        <label for="password_confirmation">{{ __('site.confirmer_le_mot_de_passe') }} <span class="required">*</span></label>
                        <input class="input" type="password" id="password_confirmation" name="password_confirmation"
                               required autocomplete="new-password">
                    </div>

                    <button class="btn btn-primary btn-block" type="submit">{{ __('site.creer_mon_compte') }}</button>
                </form>

                <p class="center mt-3" style="font-size:.9rem">
                    {{ __('site.vous_avez_deja_un_compte') }} <a class="link" href="{{ route('login') }}">{{ __('site.se_connecter') }}</a>
                </p>
            </div>
        </div>
    </section>

@endsection
