@extends('layouts.public')

@section('title', 'Commande '.$order->reference.' — '.setting('site_name'))

@section('content')

    @include('partials.page-hero', [
        'title' => __('site.commande').$order->reference,
        'crumb' => '<a href="'.route('account.index').'">'.__('site.mon_compte').'</a> › Commande',
    ])

    <section>
        <div class="wrap" style="max-width:720px">
            @include('partials.flash')

            <div class="card">
                <div class="haut" style="display:flex;justify-content:space-between;flex-wrap:wrap;gap:8px">
                    <span class="badge-soft badge-{{ $order->statusColor() }}">{{ $order->statusLabel() }}</span>
                    <small class="text-muted">Passée le {{ $order->created_at->translatedFormat('d F Y à H:i') }}</small>
                </div>

                <h3 class="mt-2">{{ __('site.produits_commandes') }}</h3>

                @foreach ($order->items as $item)
                    <div style="display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid #E3EDF1">
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

                <h3 class="mt-3">{{ __('site.livraison') }}</h3>
                <p class="text-muted">
                    {{ $order->customer_name }}<br>
                    {{ $order->phone }}<br>
                    {{ $order->address }}, {{ $order->city }}<br>
                    Paiement : {{ $order->payment_method }}
                </p>

                @if ($order->note)
                    <p class="text-muted"><b>{{ __('site.votre_message') }}</b> {{ $order->note }}</p>
                @endif

                <a class="btn btn-line mt-3" href="{{ route('account.index') }}">{{ __('site.retour_a_mes_commandes') }}</a>
            </div>
        </div>
    </section>

@endsection
