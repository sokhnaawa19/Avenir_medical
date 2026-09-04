@extends('admin.layout')

@section('title', 'Commandes')

@section('content')

    <div class="page-head">
        <p>{{ $orders->total() }} commande(s) reçue(s) depuis le site.</p>
    </div>

    <form class="filters" method="GET" action="{{ route('admin.orders.index') }}">
        <input class="input" type="search" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="🔍 Numéro, nom ou téléphone…">

        <select class="input" name="status" style="max-width:240px">
            <option value="">Tous les états</option>
            @foreach ($statuses as $key => $label)
                <option value="{{ $key }}" @selected(($filters['status'] ?? null) === $key)>{{ $label }}</option>
            @endforeach
        </select>

        <button class="btn btn-line" type="submit">Filtrer</button>
    </form>

    <div class="table-wrap">
        @if ($orders->isEmpty() && $orders->currentPage() > 1)
            <div class="empty-page">
                <span>📄</span>
                <p>Cette page ne contient plus d'éléments.</p>
                <a class="btn btn-primary btn-sm" style="margin-top:12px" href="{{ route('admin.orders.index') }}">Revenir à la première page</a>
            </div>
        @elseif ($orders->isEmpty())
            <div class="empty"><span>🧾</span>Aucune commande pour le moment.</div>
        @else
            <table>
                <thead>
                <tr><th>Commande</th><th>Client</th><th>Articles</th><th>Total</th><th>État</th><th></th></tr>
                </thead>
                <tbody>
                @foreach ($orders as $order)
                    <tr>
                        <td>
                            <b>{{ $order->reference }}</b><br>
                            <small class="hint">{{ $order->created_at->translatedFormat('d M Y à H:i') }}</small>
                        </td>
                        <td>
                            {{ $order->customer_name }}<br>
                            <small class="hint">{{ $order->phone }} · {{ $order->city }}</small>
                        </td>
                        <td>{{ $order->items_count }}</td>
                        <td><b>{{ money($order->total) }}</b><br><small class="hint">{{ $order->payment_method }}</small></td>
                        <td><span class="tag tag-{{ $order->statusColor() }}">{{ $order->statusLabel() }}</span></td>
                        <td>
                            <div class="actions">
                                <a class="btn btn-line btn-sm" href="{{ route('admin.orders.show', $order) }}">Détails</a>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>

    {{ $orders->links() }}

@endsection
