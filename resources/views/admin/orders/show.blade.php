@extends('admin.layout')

@section('title', 'Commande '.$order->reference)

@section('content')

    <div class="page-head">
        <p>Reçue le {{ $order->created_at->translatedFormat('d F Y à H:i') }}</p>
        <a class="btn btn-line" href="{{ route('admin.orders.index') }}">← Retour aux commandes</a>
    </div>

    <div class="grid g2" style="align-items:start">
        <div class="card">
            <h2>Produits commandés</h2>

            <div class="table-wrap" style="border:none">
                <table>
                    <thead>
                    <tr><th>Produit</th><th>Prix</th><th>Qté</th><th>Total</th></tr>
                    </thead>
                    <tbody>
                    @foreach ($order->items as $item)
                        <tr>
                            <td>
                                @if ($item->product)
                                    <a href="{{ route('admin.products.edit', $item->product) }}"><b>{{ $item->product_name }}</b></a>
                                @else
                                    <b>{{ $item->product_name }}</b>
                                    <small class="hint">(produit supprimé du catalogue)</small>
                                @endif
                            </td>
                            <td>{{ money($item->unit_price) }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td><b>{{ money($item->total) }}</b></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div style="margin-top:18px;display:flex;justify-content:space-between;padding:8px 0">
                <span class="hint">Sous-total</span><span>{{ money($order->subtotal) }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;padding:8px 0">
                <span class="hint">Livraison</span>
                <span>{{ $order->delivery_fee > 0 ? money($order->delivery_fee) : 'À convenir' }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;padding-top:12px;border-top:1px solid var(--line);font-family:Sora;font-weight:700;color:var(--teal-dark)">
                <span>Total</span><span>{{ money($order->total) }}</span>
            </div>
        </div>

        <div>
            <div class="card">
                <h2>État de la commande</h2>

                <form method="POST" action="{{ route('admin.orders.update', $order) }}">
                    @csrf
                    @method('PATCH')

                    <div class="field">
                        <label for="status">Suivi</label>
                        <select class="input" id="status" name="status">
                            @foreach ($statuses as $key => $label)
                                <option value="{{ $key }}" @selected($order->status === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button class="btn btn-primary" type="submit">Mettre à jour</button>
                </form>
            </div>

            <div class="card">
                <h2>Client</h2>
                <p>
                    <b>{{ $order->customer_name }}</b><br>
                    <a href="tel:{{ preg_replace('/\s+/', '', $order->phone) }}">{{ $order->phone }}</a><br>
                    @if ($order->email)
                        <a href="mailto:{{ $order->email }}">{{ $order->email }}</a><br>
                    @endif
                    {{ $order->address }}<br>
                    {{ $order->city }}
                </p>

                <p style="margin-top:12px">
                    <span class="tag">{{ $order->payment_method }}</span>
                    @if ($order->user)
                        <span class="tag tag-blue">Compte client</span>
                    @else
                        <span class="tag tag-grey">Commande sans compte</span>
                    @endif
                </p>

                @if ($order->note)
                    <p class="hint" style="margin-top:12px"><b>Message du client :</b><br>{{ $order->note }}</p>
                @endif
            </div>

            <div class="card">
                <h2>Supprimer</h2>
                <p class="hint" style="margin-bottom:12px">La commande sera définitivement effacée.</p>
                @include('admin.partials.delete-form', ['action' => route('admin.orders.destroy', $order), 'name' => 'Commande '.$order->reference])
            </div>
        </div>
    </div>

@endsection
