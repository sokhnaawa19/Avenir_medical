@extends('admin.layout')

@section('title', 'Tableau de bord')

@section('content')

    <div class="grid g4">
        <div class="stat">
            <div class="lbl">🧾 Commandes</div>
            <div class="val">{{ $stats['orders'] }}</div>
            <small class="hint">{{ $stats['pending'] }} en attente</small>
        </div>
        <div class="stat">
            <div class="lbl">💰 Chiffre d'affaires confirmé</div>
            <div class="val">{{ money($stats['revenue']) }}</div>
        </div>
        <div class="stat">
            <div class="lbl">📦 Produits</div>
            <div class="val">{{ $stats['products'] }}</div>
        </div>
        <div class="stat">
            <div class="lbl">👥 Clients</div>
            <div class="val">{{ $stats['clients'] }}</div>
            <small class="hint">{{ $stats['messages'] }} message(s) non lu(s)</small>
        </div>
    </div>

    <div class="grid g2" style="margin-top:20px">
        <div class="card">
            <h2>Dernières commandes</h2>

            @if ($latestOrders->isEmpty())
                <p class="hint">Aucune commande pour le moment.</p>
            @else
                <div class="table-wrap" style="border:none">
                    <table>
                        <tbody>
                        @foreach ($latestOrders as $order)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order) }}">
                                        <b>{{ $order->reference }}</b><br>
                                        <small class="hint">{{ $order->customer_name }} · {{ $order->created_at->diffForHumans() }}</small>
                                    </a>
                                </td>
                                <td style="text-align:right">
                                    <b>{{ money($order->total) }}</b><br>
                                    <span class="tag tag-{{ $order->statusColor() }}">{{ $order->statusLabel() }}</span>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <a class="btn btn-line btn-sm" style="margin-top:14px" href="{{ route('admin.orders.index') }}">Voir toutes les commandes</a>
            @endif
        </div>

        <div class="card">
            <h2>Derniers messages</h2>

            @if ($latestMessages->isEmpty())
                <p class="hint">Aucun message pour le moment.</p>
            @else
                <div class="table-wrap" style="border:none">
                    <table>
                        <tbody>
                        @foreach ($latestMessages as $message)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.messages.show', $message) }}">
                                        <b>{{ $message->name }}</b>
                                        @unless ($message->is_read)<span class="tag tag-orange">Nouveau</span>@endunless
                                        <br>
                                        <small class="hint">{{ str($message->message)->limit(60) }}</small>
                                    </a>
                                </td>
                                <td style="text-align:right"><small class="hint">{{ $message->created_at->diffForHumans() }}</small></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <a class="btn btn-line btn-sm" style="margin-top:14px" href="{{ route('admin.messages.index') }}">Voir tous les messages</a>
            @endif
        </div>
    </div>

    @if ($lowStock->isNotEmpty())
        <div class="card">
            <h2>⚠️ Stocks bientôt épuisés</h2>
            <div class="table-wrap" style="border:none">
                <table>
                    <tbody>
                    @foreach ($lowStock as $product)
                        <tr>
                            <td><a href="{{ route('admin.products.edit', $product) }}"><b>{{ $product->name }}</b></a></td>
                            <td style="text-align:right">
                                <span class="tag {{ $product->stock <= 0 ? 'tag-red' : 'tag-orange' }}">
                                    {{ $product->stock <= 0 ? 'Épuisé' : $product->stock.' restant(s)' }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <div class="card">
        <h2>Raccourcis</h2>
        <div class="form-actions">
            <a class="btn btn-primary" href="{{ route('admin.products.create') }}">➕ Ajouter un produit</a>
            <a class="btn btn-line" href="{{ route('admin.posts.create') }}">📝 Écrire un article</a>
            <a class="btn btn-line" href="{{ route('admin.settings.index') }}">⚙️ Modifier les réglages</a>
        </div>
    </div>

@endsection
