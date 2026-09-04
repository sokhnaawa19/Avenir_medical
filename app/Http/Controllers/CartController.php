<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(private readonly CartService $cart)
    {
    }

    public function index(): View
    {
        return view('shop.cart', [
            'items' => $this->cart->items(),
            'subtotal' => $this->cart->subtotal(),
            'deliveryFee' => $this->cart->deliveryFee(),
            'total' => $this->cart->total(),
        ]);
    }

    public function store(Request $request, Product $product): RedirectResponse
    {
        abort_unless($product->is_active, 404);

        $validated = $request->validate([
            'quantity' => ['nullable', 'integer', 'min:1', 'max:999'],
        ]);

        if ($product->isOutOfStock()) {
            return back()->with('error', 'Ce produit n’est plus disponible pour le moment.');
        }

        $this->cart->add($product, (int) ($validated['quantity'] ?? 1));

        return back()->with('success', $product->name.' a bien été ajouté à votre panier.');
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:0', 'max:999'],
        ]);

        $this->cart->update($product, (int) $validated['quantity']);

        return back()->with('success', 'Votre panier a été mis à jour.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->cart->remove($product);

        return back()->with('success', 'Le produit a été retiré de votre panier.');
    }

    public function clear(): RedirectResponse
    {
        $this->cart->clear();

        return back()->with('success', 'Votre panier a été vidé.');
    }
}
