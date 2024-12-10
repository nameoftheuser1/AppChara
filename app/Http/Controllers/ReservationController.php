<?php

namespace App\Http\Controllers;

use App\Mail\NewReservationToClient;
use App\Mail\ReservationConfirmationToUser;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Reservation;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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
            'cancelled' => Order::where('status', 'cancelled')->count(),
            'refunded' => Order::where('status', 'refunded')->count(), // Added refunded count
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
            'cancelled' => Order::where('status', 'cancelled')
                ->where('updated_at', '>=', $fourHoursAgo)
                ->count(),
            'refunded' => Order::where('status', 'refunded') // Added refunded recent updates
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

    public function cancelIndex(Request $request)
    {
        $cancelledOrders = Order::where('status', 'cancelled')
            ->latest()
            ->paginate(10);
        return view('reservations.cancel-index', [
            'cancelledOrders' => $cancelledOrders
        ]);
    }

    public function refundedIndex(Request $request)
    {
        $refundedOrders = Order::where('status', 'refunded')
            ->latest()
            ->paginate(10);
        return view('reservations.refunded-index', [
            'refundedOrders' => $refundedOrders
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
        $completeOrders = Order::where('status', 'completed')
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

    public function showReservation(Order $order)
    {
        $reservation = $order->reservation->load([
            'order.orderDetails.product' => function ($query) {
                $query->select('id', 'name', 'price', 'img_path');
            }
        ]);

        $orderDetails = $order->orderDetails->map(function ($detail) {
            return [
                'product_name' => $detail->product->name,
                'product_price' => number_format($detail->product->price, 2),
                'quantity' => $detail->quantity,
                'subtotal' => number_format($detail->quantity * $detail->product->price, 2),
                'product_image' => $detail->product->img_path ? asset($detail->product->img_path) : null,
            ];
        });

        // Include status explicitly in the reservation response
        return response()->json([
            'reservation' => array_merge($reservation->toArray(), [
                'status' => $order->status,
            ]),
            'order_details' => $orderDetails,
        ]);
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
        DB::beginTransaction(); // Start the transaction
        Log::info('Reservation creation started', ['request' => $request->all()]);

        try {
            $validated = $request->validate([
                'name' => 'required|string|max:100',
                'contact_number' => ['required', 'string', 'regex:/^\d{11}$/'],
                'email' => 'required|email|max:255',
                'coupon' => 'nullable|string|max:50',
                'pick_up_date' => 'required|date|after_or_equal:' . now()->toDateString(),
                'products' => 'required|array',
                'products.*' => 'integer|min:0',
            ]);

            Log::info('Validated request data', ['validated' => $validated]);

            // Remove products with zero quantity
            $products = array_filter($validated['products'], fn($quantity) => $quantity > 0);
            Log::info('Filtered products (non-zero quantities)', ['products' => $products]);

            if (empty($products)) {
                Log::warning('No products with quantity greater than zero');
                return redirect()->back()->withErrors(['products' => 'At least one product must have a quantity greater than zero.']);
            }

            // Generate a transaction key
            $transactionKey = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 6));
            Log::info('Generated transaction key', ['transaction_key' => $transactionKey]);

            // Create a new order
            $order = Order::create([
                'transaction_key' => $transactionKey,
                'total_amount' => 0,
                'status' => Order::STATUS_PENDING,
            ]);
            Log::info('Created order', ['order' => $order]);

            // Calculate total amount and attach products to order
            $totalAmount = 0;
            foreach ($products as $productId => $quantity) {
                $product = Product::findOrFail($productId);
                Log::info('Product found', ['product_id' => $productId, 'product_name' => $product->name]);

                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'amount' => $product->price * $quantity,
                ]);
                $totalAmount += $product->price * $quantity;

                Log::info('Added product to order', ['order_id' => $order->id, 'product_id' => $productId, 'quantity' => $quantity, 'amount' => $product->price * $quantity]);
            }

            // Update total amount in the order
            $order->update(['total_amount' => $totalAmount]);
            Log::info('Updated total amount in order', ['total_amount' => $totalAmount]);

            // Create a reservation linked to the order
            $reservation = Reservation::create([
                'transaction_key' => $transactionKey,
                'name' => $validated['name'],
                'contact_number' => $validated['contact_number'],
                'email' => $validated['email'],
                'coupon' => $validated['coupon'] ?? null,
                'pick_up_date' => $validated['pick_up_date'],
                'order_id' => $order->id,
                'user_id' => Auth::id() ?? null,
            ]);
            Log::info('Created reservation', ['reservation' => $reservation]);

            // Send confirmation email to the user
            Mail::to($validated['email'])->send(new ReservationConfirmationToUser($transactionKey, $validated['pick_up_date']));
            Log::info('Sent confirmation email to user', ['email' => $validated['email'], 'transaction_key' => $transactionKey]);

            $clientEmail = Setting::where('key', 'email')->value('value') ?? 'appchara12@gmail.com';
            Mail::to($clientEmail)->send(new NewReservationToClient($transactionKey, $validated['name'], $validated['pick_up_date'], $validated['contact_number'], $validated['email']));
            Log::info('Sent reservation email to client', ['client_email' => $clientEmail, 'transaction_key' => $transactionKey]);

            DB::commit(); // Commit the transaction if everything is successful
            Log::info('Transaction committed successfully');

            // Redirect to the check status form with the transaction key
            return redirect()->route('check.status.form')->with([
                'transaction_key' => $transactionKey,
                'success' => 'Reservation created successfully! You can check your status using the transaction key.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback the transaction if something goes wrong

            Log::error('Error during reservation creation', [
                'error_message' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
            ]);

            // Return an error message
            return redirect()->back()->withErrors(['error' => 'An error occurred. Please try again later.']);
        }
    }
}
