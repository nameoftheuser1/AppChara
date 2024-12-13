<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class DiscountTest extends TestCase
{
    public function test_apply_discount_to_cart()
    {
        // Create and authenticate an admin user
        $user = User::factory()->create(['role' => 'admin']);
        $this->actingAs($user);

        // Create a product with inventory
        $product = Product::factory()->create([
            'price' => 50.00,
        ]);

        // Create inventory for the product
        $inventory = Inventory::create([
            'product_id' => $product->id,
            'quantity' => 10
        ]);

        // Create a cart with a specific session ID
        $sessionId = session()->getId();
        $cart = Cart::create([
            'user_id' => $user->id,
            'session_id' => $sessionId
        ]);

        // Create a cart item
        $cartItem = CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'price' => $product->price
        ]);

        // Verify the cart is set up correctly
        $this->assertNotNull($cart, 'Cart should be created');
        $this->assertCount(1, $cart->cartItems, 'Cart should have one item');

        // Apply discount
        $response = $this->withSession(['_token' => csrf_token()])
            ->post(route('pos.apply-discount'), [
                'discount' => 5.00
            ]);

        // Debug information
        $sessionData = session()->all();
        dump($sessionData);

        // Assertions
        $response->assertRedirect();
        $response->assertSessionHas('success', 'Discount applied successfully.');

        // Calculate expected total (2 * 50 - 5)
        $expectedTotal = (2 * 50.00) - 5.00;

        $this->assertTrue(session()->has('discount'), 'The discount was not set in the session.');
        $this->assertEquals(5.00, session('discount'), 'Discount value is incorrect.');
        $this->assertEquals($expectedTotal, session('total'), 'Total value after discount is incorrect.');
    }
}
