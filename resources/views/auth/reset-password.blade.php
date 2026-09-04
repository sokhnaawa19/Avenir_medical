@extends('layouts.public')

@section('title', 'Nouveau mot de passe — '.setting('site_name'))

@section('content')

    @include('partials.page-hero', ['title' => 'Choisir un nouveau mot de passe', 'crumb' => 'Mon compte'])

    <section>
        <div class="wrap" style="max-width:520px">
            @include('partials.flash')

            <div class="card">
                <form method="POST" action="{{ route('password.store') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="field">
                        <label for="email">Email</label>
                        <input class="input @error('email') is-invalid @enderror" type="email" id="email" name="email"
                               value="{{ old('email', $email) }}" required>
                        @error('email')<span class="error-text">{{ $message }}</span>@enderror
                    </div>

                    <div class="field">
                        <label for="password">{{ __('site.nouveau_mot_de_passe') }}</label>
                        <input class="input @error('password') is-invalid @enderror" type="password" id="password"
                               name="password" required autocomplete="new-password">
                        @error('password')<span class="error-text">{{ $message }}</span>@enderror
                    </div>

                    <div class="field">
                        <label for="password_confirmation">{{ __('site.confirmer_le_mot_de_passe') }}</label>
                        <input class="input" type="password" id="password_confirmation" name="password_confirmation" required>
                    </div>

                    <button class="btn btn-primary btn-block" type="submit">{{ __('site.enregistrer_le_nouveau_mot_de_passe') }}</button>
                </form>
            </div>
        </div>
    </section>

@endsection
