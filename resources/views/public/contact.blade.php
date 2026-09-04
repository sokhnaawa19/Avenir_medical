@extends('layouts.public')

@section('title', 'Contact — '.setting('site_name'))

@section('content')

    @include('partials.page-hero', [
        'title' => 'Contactez-nous',
        'text' => 'Une question, un devis, un projet ? Écrivez-nous ou appelez-nous.',
        'crumb' => 'Contact',
    ])

    <section>
        <div class="wrap">
            @include('partials.flash')

            <div class="grid-2" style="align-items:start">
                <div class="card">
                    <h3>@include('partials.icon', ['name' => 'mail']) Envoyez-nous un message</h3>

                    <form method="POST" action="{{ route('contact.store') }}">
                        @csrf

                        <div class="grid-2">
                            <div class="field">
                                <label for="name">{{ __('site.prenom_et_nom') }} <span class="required">*</span></label>
                                <input class="input @error('name') is-invalid @enderror" type="text" id="name" name="name"
                                       value="{{ old('name', auth()->user()->name ?? '') }}" placeholder="{{ __('site.ex_awa_diop') }}" required>
                                @error('name')<span class="error-text">{{ $message }}</span>@enderror
                            </div>

                            <div class="field">
                                <label for="phone">{{ __('site.telephone') }}</label>
                                <input class="input @error('phone') is-invalid @enderror" type="tel" id="phone" name="phone"
                                       value="{{ old('phone', auth()->user()->phone ?? '') }}" placeholder="{{ __('site.ex_77_123_45_67') }}">
                                @error('phone')<span class="error-text">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        <div class="field">
                            <label for="email">Email</label>
                            <input class="input @error('email') is-invalid @enderror" type="email" id="email" name="email"
                                   value="{{ old('email', auth()->user()->email ?? '') }}" placeholder="{{ __('site.votre_email_com') }}">
                            @error('email')<span class="error-text">{{ $message }}</span>@enderror
                        </div>

                        <div class="field">
                            <label for="subject">{{ __('site.objet') }}</label>
                            <input class="input" type="text" id="subject" name="subject"
                                   value="{{ old('subject') }}"
                                   placeholder="{{ __('site.ex_demande_de_devis') }}">
                        </div>

                        <div class="field">
                            <label for="message">{{ __('site.votre_message') }} <span class="required">*</span></label>
                            <textarea class="input @error('message') is-invalid @enderror" id="message" name="message"
                                      placeholder="{{ __('site.decrivez_votre_besoin') }}" required>{{ old('message') }}</textarea>
                            @error('message')<span class="error-text">{{ $message }}</span>@enderror
                        </div>

                        <button class="btn btn-primary btn-block" type="submit">{{ __('site.envoyer_le_message') }}</button>
                    </form>
                </div>

                <div>
                    <div class="card mt-2">
                        <h3>@include('partials.icon', ['name' => 'phone']) Par téléphone</h3>
                        <p>
                            @foreach (['phone_1', 'phone_2', 'phone_3'] as $phone)
                                @if (setting($phone))
                                    <a class="link" href="tel:{{ preg_replace('/\s+/', '', setting($phone)) }}">{{ setting($phone) }}</a><br>
                                @endif
                            @endforeach
                        </p>
                    </div>

                    <div class="card mt-2">
                        <h3>@include('partials.icon', ['name' => 'mail']) Par email</h3>
                        <p><a class="link" href="mailto:{{ setting('email') }}">{{ setting('email') }}</a></p>
                    </div>

                    <div class="card mt-2">
                        <h3>@include('partials.icon', ['name' => 'pin']) Où nous trouver</h3>
                        <p>{!! nl2br(e((string) setting('address'))) !!}<br>
                            <small class="text-muted">{{ setting('opening_hours') }}</small>
                        </p>
                    </div>
                </div>
            </div>

            @if (setting('map_embed'))
                <div class="map-embed mt-3">{!! setting('map_embed') !!}</div>
            @endif
        </div>
    </section>

@endsection
