@extends('layouts.public')

@section('title', 'Mon compte — '.setting('site_name'))

@section('content')

    @include('partials.page-hero', ['title' => 'Mon compte', 'crumb' => 'Mon compte'])

    <section>
        <div class="wrap" style="max-width:820px">
            @include('partials.flash')

            <div class="card">
                <h3>@include('partials.icon', ['name' => 'wave']) Bonjour, {{ auth()->user()->name }} !</h3>
                <p class="text-muted">
                    Email : {{ auth()->user()->email }}
                    @if (auth()->user()->phone)
                        <br>Téléphone : {{ auth()->user()->phone }}
                    @endif
                </p>

                <div class="mt-2" style="display:flex;gap:12px;flex-wrap:wrap">
                    <a class="btn btn-line btn-sm" href="{{ route('account.profile') }}">{{ __('site.modifier_mes_informations') }}</a>

                    @if (auth()->user()->isAdmin())
                        <a class="btn btn-primary btn-sm" href="{{ route('admin.dashboard') }}">{{ __('site.administration_du_site') }}</a>
                    @endif

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn btn-line btn-sm" type="submit">{{ __('site.se_deconnecter') }}</button>
                    </form>
                </div>
            </div>

            <h3 style="font-family:Sora;color:var(--teal-dark);margin:34px 0 16px">@include('partials.icon', ['name' => 'box']) Mes commandes</h3>

            @if ($orders->isEmpty())
                <p class="text-muted">
                    {{ __('site.vous_navez_pas_encore_passe_de_commande') }}
                    <a class="link" href="{{ route('shop.index') }}">{{ __('site.decouvrir_la_boutique') }}</a>
                </p>
            @else
                @foreach ($orders as $order)
                    <div class="commande">
                        <div class="haut">
                            <span class="num">{{ $order->reference }}</span>
                            <span class="badge-soft badge-{{ $order->statusColor() }}">{{ $order->statusLabel() }}</span>
                        </div>

                        <small class="text-muted">
                            Passée le {{ $order->created_at->translatedFormat('d F Y') }} · {{ $order->payment_method }}
                        </small>

                        <ul style="margin:10px 0">
                            @foreach ($order->items as $item)
                                <li>• {{ $item->product_name }} × {{ $item->quantity }} — {{ money($item->total) }}</li>
                            @endforeach
                        </ul>

                        <b style="font-family:Sora;color:var(--teal)">Total : {{ money($order->total) }}</b>
                        <a class="link" style="float:right" href="{{ route('account.order', $order) }}">{{ __('site.voir_le_detail') }}</a>
                    </div>
                @endforeach

                {{ $orders->links() }}
            @endif
        </div>
    </section>

@endsection
