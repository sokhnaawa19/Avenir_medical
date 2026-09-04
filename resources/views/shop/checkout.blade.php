@extends('layouts.public')

@section('title', 'Finaliser ma commande — '.setting('site_name'))

@section('content')

    @include('partials.page-hero', [
        'title' => 'Finaliser ma commande',
        'crumb' => '<a href="'.lroute('cart.index').'">'.__('site.panier').'</a> › Commande',
    ])

    <section>
        <div class="wrap">
            @include('partials.flash')

            @guest
                <div class="alert alert-success" style="background:var(--tint);color:var(--teal-dark);border-color:#CDE5EC">
                    @include('partials.icon', ['name' => 'bulb']) Vous pouvez commander sans compte. <a class="link" href="{{ route('login') }}">{{ __('site.connectez_vous') }}</a>
                    ou <a class="link" href="{{ route('register') }}">'.__('site.creez_un_compte').'</a> '.__('site.pour_suivre_vos_commandes').'
                </div>
            @endguest

            <form method="POST" action="{{ lroute('checkout.store') }}">
                @csrf

                <div class="shop-layout">
                    <div class="card">
                        <h3>@include('partials.icon', ['name' => 'list']) Vos informations de livraison</h3>

                        <div class="grid-2">
                            <div class="field">
                                <label for="customer_name">{{ __('site.prenom_et_nom') }} <span class="required">*</span></label>
                                <input class="input @error('customer_name') is-invalid @enderror" type="text"
                                       id="customer_name" name="customer_name"
                                       value="{{ old('customer_name', auth()->user()->name ?? '') }}" required>
                                @error('customer_name')<span class="error-text">{{ $message }}</span>@enderror
                            </div>

                            <div class="field">
                                <label for="phone">{{ __('site.telephone') }} <span class="required">*</span></label>
                                <input class="input @error('phone') is-invalid @enderror" type="tel" id="phone" name="phone"
                                       value="{{ old('phone', auth()->user()->phone ?? '') }}" placeholder="{{ __('site.ex_77_123_45_67') }}" required>
                                @error('phone')<span class="error-text">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        <div class="field">
                            <label for="email">Email</label>
                            <input class="input @error('email') is-invalid @enderror" type="email" id="email" name="email"
                                   value="{{ old('email', auth()->user()->email ?? '') }}">
                            <span class="form-hint">{{ __('site.pour_recevoir_le_suivi_de_votre_commande') }}</span>
                            @error('email')<span class="error-text">{{ $message }}</span>@enderror
                        </div>

                        <div class="field">
                            <label for="address">{{ __('site.adresse_de_livraison') }} <span class="required">*</span></label>
                            <input class="input @error('address') is-invalid @enderror" type="text" id="address" name="address"
                                   value="{{ old('address', auth()->user()->address ?? '') }}" placeholder="{{ __('site.quartier_rue_repere') }}" required>
                            @error('address')<span class="error-text">{{ $message }}</span>@enderror
                        </div>

                        <div class="field">
                            <label for="city">{{ __('site.ville') }} <span class="required">*</span></label>
                            <input class="input @error('city') is-invalid @enderror" type="text" id="city" name="city"
                                   value="{{ old('city', auth()->user()->city ?? 'Dakar') }}" required>
                            @error('city')<span class="error-text">{{ $message }}</span>@enderror
                        </div>

                        @php $methods = payment_methods(); @endphp

                        @if (count($methods) === 1)
                            {{-- Un seul moyen de paiement : on l'annonce, sans faire choisir --}}
                            <div class="field">
                                <label>{{ __('site.mode_de_paiement') }}</label>
                                <p class="pay-single">
                                    <span aria-hidden="true">@include('partials.icon', ['name' => 'cash'])</span> {{ $methods[0] }}
                                </p>
                                <input type="hidden" name="payment_method" value="{{ $methods[0] }}">
                            </div>
                        @else
                            <div class="field">
                                <label>{{ __('site.mode_de_paiement') }} <span class="required">*</span></label>
                                <div class="radio-row">
                                    @foreach ($methods as $method)
                                        <label class="radio-card">
                                            <input type="radio" name="payment_method" value="{{ $method }}"
                                                   @checked(old('payment_method', $methods[0] ?? '') === $method)>
                                            {{ $method }}
                                        </label>
                                    @endforeach
                                </div>
                                @error('payment_method')<span class="error-text">{{ $message }}</span>@enderror
                            </div>
                        @endif

                        <div class="field">
                            <label for="note">Message (facultatif)</label>
                            <textarea class="input" id="note" name="note" placeholder="{{ __('site.une_precision_pour_la_livraison') }}">{{ old('note') }}</textarea>
                        </div>

                        <button class="btn btn-primary btn-block" type="submit">@include('partials.icon', ['name' => 'check']) Confirmer ma commande</button>
                    </div>

                    <div class="totaux">
                        <h3 style="font-family:Sora;color:var(--teal-dark);margin-bottom:10px">{{ __('site.votre_commande') }}</h3>

                        @foreach ($items as $item)
                            <div class="ligne">
                                <span>{{ $item->product->name }} × {{ $item->quantity }}</span>
                                <span>{{ money($item->total) }}</span>
                            </div>
                        @endforeach

                        <div class="ligne">
                            <span>{{ __('site.livraison') }}</span>
                            <span>{{ $deliveryFee > 0 ? money($deliveryFee) : 'À convenir' }}</span>
                        </div>
                        <div class="ligne total"><span>{{ __('site.total') }}</span><span>{{ money($total) }}</span></div>

                        <a class="link" style="display:block;text-align:center;margin-top:14px;font-size:.9rem" href="{{ lroute('cart.index') }}">
                            {{ __('site.modifier_mon_panier') }}
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </section>

@endsection
