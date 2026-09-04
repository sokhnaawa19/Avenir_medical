<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShopTest extends TestCase
{
    use RefreshDatabase;

    public function test_un_visiteur_peut_commander(): void
    {
        $this->seed(\Database\Seeders\SettingsSeeder::class);

        $category = Category::query()->create(['name' => 'Petits matériels']);
        $product = Product::query()->create([
            'category_id' => $category->id,
            'name' => 'Tensiomètre',
            'price' => 45000,
            'is_active' => true,
        ]);

        // Ajout au panier
        $this->post(route('cart.store', $product))->assertRedirect();
        $this->assertSame(1, cart()->count());

        // Passage de la commande
        $this->post(route('checkout.store'), [
            'customer_name' => 'Moussa Ndiaye',
            'phone' => '77 000 00 00',
            'address' => 'Médina',
            'city' => 'Dakar',
            'payment_method' => payment_methods()[0],
        ])->assertRedirect();

        $this->assertDatabaseHas('orders', ['customer_name' => 'Moussa Ndiaye', 'total' => 45000]);
        $this->assertDatabaseHas('order_items', ['product_name' => 'Tensiomètre', 'quantity' => 1]);
        $this->assertTrue(cart()->isEmpty());
    }
}
