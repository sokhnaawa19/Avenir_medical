@extends('layouts.public')

@section('title', 'Mes informations — '.setting('site_name'))

@section('content')

    @include('partials.page-hero', [
        'title' => __('site.mes_informations'),
        'crumb' => '<a href="'.route('account.index').'">'.__('site.mon_compte').'</a> › Informations',
    ])

    <section>
        <div class="wrap" style="max-width:560px">
            @include('partials.flash')

            <div class="card">
                <form method="POST" action="{{ route('account.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="field">
                        <label for="name">{{ __('site.prenom_et_nom') }} <span class="required">*</span></label>
                        <input class="input @error('name') is-invalid @enderror" type="text" id="name" name="name"
                               value="{{ old('name', auth()->user()->name) }}" required>
                        @error('name')<span class="error-text">{{ $message }}</span>@enderror
                    </div>

                    <div class="field">
                        <label for="email">Email <span class="required">*</span></label>
                        <input class="input @error('email') is-invalid @enderror" type="email" id="email" name="email"
                               value="{{ old('email', auth()->user()->email) }}" required>
                        @error('email')<span class="error-text">{{ $message }}</span>@enderror
                    </div>

                    <div class="field">
                        <label for="phone">{{ __('site.telephone') }}</label>
                        <input class="input" type="tel" id="phone" name="phone" value="{{ old('phone', auth()->user()->phone) }}">
                    </div>

                    <div class="field">
                        <label for="address">{{ __('site.adresse') }}</label>
                        <input class="input" type="text" id="address" name="address" value="{{ old('address', auth()->user()->address) }}">
                    </div>

                    <div class="field">
                        <label for="city">{{ __('site.ville') }}</label>
                        <input class="input" type="text" id="city" name="city" value="{{ old('city', auth()->user()->city) }}">
                    </div>

                    <hr style="border:none;border-top:1px solid #E3EDF1;margin:22px 0">

                    <div class="field">
                        <label for="password">{{ __('site.nouveau_mot_de_passe') }}</label>
                        <input class="input @error('password') is-invalid @enderror" type="password" id="password" name="password"
                               autocomplete="new-password">
                        <span class="form-hint">{{ __('site.laissez_vide_pour_garder_votre_mot_de_passe_') }}</span>
                        @error('password')<span class="error-text">{{ $message }}</span>@enderror
                    </div>

                    <div class="field">
                        <label for="password_confirmation">{{ __('site.confirmer_le_nouveau_mot_de_passe') }}</label>
                        <input class="input" type="password" id="password_confirmation" name="password_confirmation"
                               autocomplete="new-password">
                    </div>

                    <button class="btn btn-primary btn-block" type="submit">{{ __('site.enregistrer') }}</button>
                </form>
            </div>
        </div>
    </section>

@endsection
