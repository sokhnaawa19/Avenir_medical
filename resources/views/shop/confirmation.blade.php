@extends('layouts.public')

@section('title', 'Commande '.$order->reference.' — '.setting('site_name'))

@section('content')

    @include('partials.page-hero', ['title' => 'Merci pour votre commande !', 'crumb' => 'Commande confirmée'])

    <section>
        <div class="wrap">
            <div class="vide">
                <span>@include('partials.icon', ['name' => 'party', 'size' => 64])</span>
                <h2 style="font-family:Sora;color:var(--teal-dark);margin-bottom:10px">{{ __('site.votre_commande_est_enregistree') }}</h2>
                <p>{{ __('site.numero_de_commande') }} <b style="font-family:Sora;color:var(--teal)">{{ $order->reference }}</b></p>
                <p class="text-muted">Notre équipe vous appellera très vite au {{ $order->phone }} pour confirmer la livraison.</p>
            </div>

            <div class="card" style="max-width:640px;margin:0 auto">
                <h3>{{ __('site.recapitulatif') }}</h3>

                @foreach ($order->items as $item)
                    <div class="ligne" style="display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid #E3EDF1">
                        <span>{{ $item->product_name }} × {{ $item->quantity }}</span>
                        <span>{{ money($item->total) }}</span>
                    </div>
                @endforeach

                <div style="display:flex;justify-content:space-between;padding:10px 0">
                    <span>{{ __('site.livraison') }}</span>
                    <span>{{ $order->delivery_fee > 0 ? money($order->delivery_fee) : 'À convenir' }}</span>
                </div>

                <div style="display:flex;justify-content:space-between;padding-top:14px;border-top:1px solid #E3EDF1;font-family:Sora;font-weight:700;color:var(--teal-dark)">
                    <span>{{ __('site.total') }}</span>
                    <span>{{ money($order->total) }}</span>
                </div>

                <p class="text-muted mt-2" style="font-size:.9rem">
                    {{ __('site.mode_de_paiement_choisi') }} <b>{{ $order->payment_method }}</b><br>
                    Livraison : {{ $order->address }}, {{ $order->city }}
                </p>
            </div>

            <div class="center mt-3">
                <a class="btn btn-primary" href="{{ lroute('shop.index') }}">{{ __('site.retour_a_la_boutique') }}</a>
                @auth
                    <a class="btn btn-line" href="{{ route('account.index') }}">{{ __('site.voir_mes_commandes') }}</a>
                @endauth
            </div>
        </div>
    </section>

@endsection
