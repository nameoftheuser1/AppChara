<?php

namespace Tests\Unit;

use App\Models\Order;
use App\Models\Product;
use App\Models\OrderDetail;
use App\Models\Reservation;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use App\Mail\ReservationConfirmationToUser;
use App\Mail\NewReservationToClient;
use App\Models\User;

class ReservationStoreTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test successful reservation creation.
     *
     * @return void
     */
    public function test_reservation_store_success()
    {
        // Set up a mock product
        $product = Product::factory()->create([
            'price' => 100,
        ]);

        // Set up mock setting for client email
        Setting::create([
            'key' => 'email',
            'value' => 'client@example.com',
        ]);

        // Simulate an authenticated user
        $user = User::factory()->create();
        $this->actingAs($user);

        // Prepare request data
        $requestData = [
            'name' => 'John Doe',
            'contact_number' => '09123456789',
            'email' => 'john@example.com',
            'coupon' => null,
            'pick_up_date' => now()->addDays(1)->toDateString(),
            'products' => [
                $product->id => 2, // 2 quantities of the product
            ],
        ];

        // Mock email sending
        Mail::fake();

        // Mock database transaction
        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('commit')->once();
        DB::shouldReceive('rollBack')->never();

        // Perform the reservationStore method
        $response = $this->post(route('reservation.store'), $requestData);

        // Assertions
        $response->assertRedirect(route('check.status.form'));
        $response->assertSessionHas('success', 'Reservation created successfully! You can check your status using the transaction key.');

        // Check if the Order and Reservation are created
        $this->assertDatabaseHas('orders', [
            'transaction_key' => $response->getData()['transaction_key'],
            'total_amount' => 200, // 2 * product price (100)
        ]);

        $this->assertDatabaseHas('reservations', [
            'name' => 'John Doe',
            'contact_number' => '09123456789',
            'email' => 'john@example.com',
            'pick_up_date' => $requestData['pick_up_date'],
        ]);

        // Check if emails were sent
        Mail::assertSent(ReservationConfirmationToUser::class, function ($mail) use ($requestData) {
            return $mail->hasTo($requestData['email']);
        });

        Mail::assertSent(NewReservationToClient::class, function ($mail) use ($requestData) {
            return $mail->hasTo('client@example.com');
        });
    }

    /**
     * Test reservation creation with invalid data.
     *
     * @return void
     */
    public function test_reservation_store_invalid_data()
    {
        // Simulate an authenticated user
        $user = User::factory()->create();
        $this->actingAs($user);

        // Prepare invalid request data
        $requestData = [
            'name' => '',
            'contact_number' => 'invalid',
            'email' => 'invalid-email',
            'pick_up_date' => now()->subDays(1)->toDateString(), // Invalid past date
            'products' => [],
        ];

        // Perform the reservationStore method
        $response = $this->post(route('reservation.store'), $requestData);

        // Assertions
        $response->assertSessionHasErrors(['name', 'contact_number', 'email', 'pick_up_date', 'products']);
        $this->assertDatabaseCount('orders', 0); // No order should be created
        $this->assertDatabaseCount('reservations', 0); // No reservation should be created
    }
}
