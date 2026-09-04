<?php

namespace App\Http\Controllers;

use App\Http\Requests\Shop\StoreOrderRequest;
use App\Models\Order;
use App\Services\CartService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CheckoutController extends Controller
{
    public function __construct(private readonly CartService $cart)
    {
    }

    public function create(): View|RedirectResponse
    {
        if ($this->cart->isEmpty()) {
            return redirect()->route('shop.index')
                ->with('error', 'Votre panier est vide : ajoutez d’abord des produits.');
        }

        return view('shop.checkout', [
            'items' => $this->cart->items(),
            'subtotal' => $this->cart->subtotal(),
            'deliveryFee' => $this->cart->deliveryFee(),
            'total' => $this->cart->total(),
        ]);
    }

    public function store(StoreOrderRequest $request): RedirectResponse
    {
        if ($this->cart->isEmpty()) {
            return redirect()->route('shop.index')
                ->with('error', 'Votre panier est vide : ajoutez d’abord des produits.');
        }

        $items = $this->cart->items();
        $subtotal = $this->cart->subtotal();
        $deliveryFee = $this->cart->deliveryFee();

        $order = DB::transaction(function () use ($request, $items, $subtotal, $deliveryFee): Order {
            /** @var Order $order */
            $order = Order::query()->create([
                'reference' => 'TEMP-'.uniqid(),
                'user_id' => $request->user()?->id,
                'customer_name' => $request->validated('customer_name'),
                'phone' => $request->validated('phone'),
                'email' => $request->validated('email'),
                'address' => $request->validated('address'),
                'city' => $request->validated('city'),
                'payment_method' => $request->validated('payment_method'),
                'note' => $request->validated('note'),
                'subtotal' => $subtotal,
                'delivery_fee' => $deliveryFee,
                'total' => $subtotal + $deliveryFee,
                'status' => Order::STATUS_PENDING,
            ]);

            $order->update(['reference' => $this->buildReference($order)]);

            foreach ($items as $item) {
                $order->items()->create([
                    'product_id' => $item->product->id,
                    'product_name' => $item->product->name,
                    'unit_price' => $item->product->sellingPrice(),
                    'quantity' => $item->quantity,
                    'total' => $item->total,
                ]);

                // Mise a jour du stock lorsqu'il est suivi.
                if ($item->product->stock !== null) {
                    $item->product->decrement('stock', min($item->quantity, $item->product->stock));
                }
            }

            return $order;
        });

        $this->cart->clear();
        $this->notifyTeam($order);

        // Garde la reference en session : le client peut revenir sur la page
        // de confirmation ou la rafraichir sans perdre l'acces.
        $request->session()->put('last_order_reference', $order->reference);

        return redirect()->route('checkout.confirmation', $order);
    }

    public function confirmation(Order $order): View
    {
        $isOwner = session('last_order_reference') === $order->reference
            || (auth()->check() && (auth()->id() === $order->user_id || auth()->user()->isAdmin()));

        abort_unless($isOwner, 403);

        return view('shop.confirmation', [
            'order' => $order->load('items'),
        ]);
    }

    /**
     * Numero lisible du type AM-2026-0001.
     */
    private function buildReference(Order $order): string
    {
        $prefix = (string) setting('order_prefix', 'AM');

        return sprintf('%s-%s-%04d', $prefix, now()->format('Y'), $order->id);
    }

    /**
     * Previent l'equipe par email, sans bloquer la commande en cas de souci.
     */
    private function notifyTeam(Order $order): void
    {
        $recipient = (string) setting('email_orders');

        if (blank($recipient)) {
            return;
        }

        try {
            $body = "Nouvelle commande {$order->reference}\n"
                ."Client : {$order->customer_name} ({$order->phone})\n"
                ."Livraison : {$order->address}, {$order->city}\n"
                ."Paiement : {$order->payment_method}\n"
                ."Total : ".money($order->total)."\n";

            Mail::raw($body, function ($message) use ($recipient, $order): void {
                $message->to($recipient)->subject('Nouvelle commande '.$order->reference);
            });
        } catch (\Throwable $exception) {
            Log::warning('Impossible d’envoyer l’email de commande : '.$exception->getMessage());
        }
    }
}
