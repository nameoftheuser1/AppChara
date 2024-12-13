<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Inventory;
use App\Models\SaleDetail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class PosControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test if adding an item to the cart works correctly.
     *
     * @return void
     */
    public function test_add_item_to_cart()
    {
        // Create a product and inventory
        $product = Product::factory()->create();
        Inventory::create([
            'product_id' => $product->id,
            'quantity' => 10,
        ]);

        // Simulate a user with the admin role
        $user = User::factory()->create([
            'role' => 'admin', // Ensure the user has the admin role
        ]);
        Auth::login($user);

        // Make a request to add an item to the cart
        $response = $this->post(route('pos.add-item'), [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        // Check if the response is successful
        $response->assertRedirect();

        // Verify the cart has the item
        $this->assertDatabaseHas('cart_items', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);
    }


    public function test_remove_item_from_cart()
    {
        // Create a user first
        $user = User::factory()->create([
            'role' => 'admin', // Ensure the user has the admin role
        ]);
        Auth::login($user);

        // Create a product and cart with the user
        $product = Product::factory()->create();
        $cart = Cart::factory()->create([
            'user_id' => $user->id,
            'session_id' => $this->app['session']->getId()
        ]);

        $cartItem = CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => $product->price,
        ]);

        // Rest of the test remains the same
        $response = $this->delete(route('pos.remove-item'), [
            'item_id' => $cartItem->id
        ]);

        $response->assertRedirect();
        $this->assertDatabaseMissing('cart_items', [
            'id' => $cartItem->id
        ]);
    }
}
