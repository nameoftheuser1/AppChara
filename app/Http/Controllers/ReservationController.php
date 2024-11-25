<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Reservation;
use App\Models\Product;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }


    /**
     * Display the specified resource.
     */
    public function show(Reservation $reservation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reservation $reservation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reservation $reservation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reservation $reservation)
    {
        //
    }

    public function reservationForm()
    {
        // Get paginated products (10 per page, adjust the number as needed)
        $products = Product::with('inventory')->paginate(9);  // Adjust the number based on your layout

        // Pass the products to the view
        return view('homepage.reserve', compact('products'));
    }


    public function reservationStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'contact_number' => 'required|string|max:15',
            'coupon' => 'nullable|string|max:50',
            'pick_up_date' => 'required|date',
            'products' => 'required|array', // Ensure products are provided
            'products.*' => 'integer|min:0', // Ensure quantity is an integer >= 0
        ]);

        // Remove products with zero quantity
        $products = array_filter($validated['products'], fn($quantity) => $quantity > 0);

        if (empty($products)) {
            return redirect()->back()->withErrors(['products' => 'At least one product must have a quantity greater than zero.']);
        }

        // Generate a transaction key
        $transactionKey = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 6));

        // Create a new order
        $order = Order::create([
            'transaction_key' => $transactionKey,
            'total_amount' => 0, // Calculate later
            'status' => Order::STATUS_PENDING,
        ]);

        // Calculate total amount and attach products to order
        $totalAmount = 0;
        foreach ($products as $productId => $quantity) {
            $product = Product::findOrFail($productId); // Retrieve product for price and validation

            // Create order details
            OrderDetail::create([
                'order_id' => $order->id,
                'product_id' => $productId,
                'quantity' => $quantity,
                'amount' => $product->price * $quantity,
            ]);

            // Update total amount
            $totalAmount += $product->price * $quantity;
        }

        // Update total amount in the order
        $order->update(['total_amount' => $totalAmount]);

        // Create a reservation linked to the order
        $reservation = Reservation::create([
            'transaction_key' => $transactionKey,
            'name' => $validated['name'],
            'contact_number' => $validated['contact_number'],
            'coupon' => $validated['coupon'] ?? null,
            'pick_up_date' => $validated['pick_up_date'],
            'order_id' => $order->id,
        ]);

        return redirect()->route('homepage.reserve')->with('success', 'Reservation created successfully!');
    }
}
