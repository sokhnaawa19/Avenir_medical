<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Session\Store;
use Illuminate\Support\Collection;

/**
 * Panier d'achat conserve dans la session du visiteur.
 *
 * Structure enregistree : [identifiant du produit => quantite]
 */
class CartService
{
    private const SESSION_KEY = 'cart';

    public function __construct(private readonly Store $session)
    {
    }

    /**
     * @return array<int, int>
     */
    public function raw(): array
    {
        return (array) $this->session->get(self::SESSION_KEY, []);
    }

    /**
     * Lignes du panier avec le produit correspondant.
     *
     * @return Collection<int, object>
     */
    public function items(): Collection
    {
        $quantities = $this->raw();

        if ($quantities === []) {
            return collect();
        }

        return Product::query()
            ->active()
            ->whereIn('id', array_keys($quantities))
            ->get()
            ->map(function (Product $product) use ($quantities): object {
                $quantity = (int) ($quantities[$product->id] ?? 0);

                return (object) [
                    'product' => $product,
                    'quantity' => $quantity,
                    'total' => $product->sellingPrice() * $quantity,
                ];
            })
            ->values();
    }

    public function add(Product $product, int $quantity = 1): void
    {
        $cart = $this->raw();
        $quantity = max(1, $quantity);

        $cart[$product->id] = ($cart[$product->id] ?? 0) + $quantity;

        $this->save($cart);
    }

    public function update(Product $product, int $quantity): void
    {
        $cart = $this->raw();

        if ($quantity <= 0) {
            unset($cart[$product->id]);
        } else {
            $cart[$product->id] = $quantity;
        }

        $this->save($cart);
    }

    public function remove(Product $product): void
    {
        $cart = $this->raw();

        unset($cart[$product->id]);

        $this->save($cart);
    }

    public function clear(): void
    {
        $this->session->forget(self::SESSION_KEY);
    }

    public function isEmpty(): bool
    {
        return $this->raw() === [];
    }

    /**
     * Nombre total d'articles (utilise pour la pastille du panier).
     */
    public function count(): int
    {
        return (int) array_sum($this->raw());
    }

    public function subtotal(): int
    {
        return (int) $this->items()->sum('total');
    }

    public function deliveryFee(): int
    {
        return app(SettingsRepository::class)->integer('delivery_fee');
    }

    public function total(): int
    {
        return $this->subtotal() + $this->deliveryFee();
    }

    /**
     * @param  array<int, int>  $cart
     */
    private function save(array $cart): void
    {
        $this->session->put(self::SESSION_KEY, array_filter($cart, fn (int $quantity): bool => $quantity > 0));
    }
}
