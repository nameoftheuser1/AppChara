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
        // Get current time and 4 hours ago
        $fourHoursAgo = now()->subHours(4);

        // Basic counts
        $counts = [
            'pending' => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'ready_to_pickup' => Order::where('status', 'ready to pickup')->count(),
            'completed' => Order::where('status', 'completed')->count(),
            'total' => Order::count(),
        ];

        // Recent updates in last 4 hours
        $recentUpdates = [
            'pending' => Order::where('status', 'pending')
                ->where('updated_at', '>=', $fourHoursAgo)
                ->count(),
            'processing' => Order::where('status', 'processing')
                ->where('updated_at', '>=', $fourHoursAgo)
                ->count(),
            'ready_to_pickup' => Order::where('status', 'ready to pickup')
                ->where('updated_at', '>=', $fourHoursAgo)
                ->count(),
            'completed' => Order::where('status', 'completed')
                ->where('updated_at', '>=', $fourHoursAgo)
                ->count(),
        ];

        // Get recent orders with details
        $recentOrders = Order::with('orderDetails')
            ->where('updated_at', '>=', $fourHoursAgo)
            ->latest('updated_at')
            ->get();

        return view('reservations.index', compact('counts', 'recentUpdates', 'recentOrders'));
    }

    public function pendingIndex(Request $request)
    {
        $pendingOrders = Order::where('status', 'pending')
            ->latest()
            ->paginate(10);

        return view('reservations.pending-index', [
            'pendingOrders' => $pendingOrders
        ]);
    }

    public function processingIndex(Request $request)
    {
        $processingOrders = Order::where('status', 'processing')
            ->latest()
            ->paginate(10);

        return view('reservations.processing-index', [
            'processingOrders' => $processingOrders
        ]);
    }

    public function readyToPickUpIndex(Request $request)
    {
        $readyToPickUpOrders = Order::where('status', 'ready to pickup')
            ->latest()
            ->paginate(10);

        return view('reservations.ready-to-pickup-index', [
            'readyToPickUpOrders' => $readyToPickUpOrders
        ]);
    }

    public function completeIndex(Request $request)
    {
        $completeOrders = Order::where('status', 'complete')
            ->latest()
            ->paginate(10);

        return view('reservations.complete-index', [
            'completeOrders' => $completeOrders
        ]);
    }

    public function allIndex(Request $request)
    {
        $allOrders = Order::with('reservation')->latest()->paginate(10);

        return view('reservations.all-index', [
            'allOrders' => $allOrders,
        ]);
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
            'contact_number' => ['required', 'string', 'regex:/^\d{11}$/'],
            'email' => 'required|email|max:255',
            'coupon' => 'nullable|string|max:50',
            'pick_up_date' => 'required|date',
            'products' => 'required|array',
            'products.*' => 'integer|min:0',
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
            'total_amount' => 0,
            'status' => Order::STATUS_PENDING,
        ]);

        // Calculate total amount and attach products to order
        $totalAmount = 0;
        foreach ($products as $productId => $quantity) {
            $product = Product::findOrFail($productId);

            OrderDetail::create([
                'order_id' => $order->id,
                'product_id' => $productId,
                'quantity' => $quantity,
                'amount' => $product->price * $quantity,
            ]);

            $totalAmount += $product->price * $quantity;
        }

        // Update total amount in the order
        $order->update(['total_amount' => $totalAmount]);

        // Create a reservation linked to the order
        $reservation = Reservation::create([
            'transaction_key' => $transactionKey,
            'name' => $validated['name'],
            'contact_number' => $validated['contact_number'],
            'email' => $validated['email'],  // Save email to the reservation
            'coupon' => $validated['coupon'] ?? null,
            'pick_up_date' => $validated['pick_up_date'],
            'order_id' => $order->id,
        ]);

        // Redirect to the check status form with the transaction key
        return redirect()->route('check.status.form')->with([
            'transaction_key' => $transactionKey,
            'success' => 'Reservation created successfully! You can check your status using the transaction key.',
        ]);
    }
}
