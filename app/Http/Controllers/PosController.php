<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PosController extends Controller
{

    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->with('inventory')
            ->orderBy('name')
            ->paginate(12);


        $cart = Cart::with('items.product')
            ->where('session_id', session()->getId())
            ->first();

        $cart_items = $cart ? $cart->items : collect();

        $subtotal = $cart_items->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        $discount = session('discount', 0);
        $total = $subtotal - $discount;

        return view('pos.index', compact('products', 'cart_items', 'subtotal', 'total', 'discount'));
    }

    public function addItem(Request $request)
    {
        DB::beginTransaction(); // Start the transaction

        try {
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1'
            ]);

            $product = Product::findOrFail($request->product_id);
            // Find or create the current session's cart
            $cart = Cart::firstOrCreate(
                ['session_id' => session()->getId()],
                ['user_id' => Auth::id()]
            );

            // Check if the product already exists in the cart
            $cartItem = $cart->cartItems()->where('product_id', $product->id)->first();

            $currentCartQuantity = $cartItem ? $cartItem->quantity : 0;
            $newTotalQuantity = $currentCartQuantity + $request->quantity;

            // Check if sufficient stock is available
            if ($product->inventory->quantity < $newTotalQuantity) {
                return back()->with('error', 'Insufficient stock');
            }

            if ($cartItem) {
                // Update the quantity if the product is already in the cart
                $cartItem->update(['quantity' => $newTotalQuantity]);
            } else {
                // Add a new cart item if it doesn't exist
                CartItem::create([
                    'cart_id' => $cart->id,
                    'product_id' => $product->id,
                    'quantity' => $request->quantity,
                    'price' => $product->price
                ]);
            }

            // Commit the transaction if everything is successful
            DB::commit();

            return back()->with('success', 'Item added to cart');
        } catch (\Exception $e) {
            // Rollback the transaction if something goes wrong
            DB::rollBack();

            // Return with error message
            return back()->with('error', 'An error occurred. Please try again.');
        }
    }

    public function removeItem(Request $request)
    {
        // Validate the item_id
        $validated = $request->validate([
            'item_id' => ['required', 'integer', 'exists:cart_items,id'],
        ]);

        // Find the cart item by item_id and session_id
        $cartItem = CartItem::where('id', $validated['item_id'])
            ->whereHas('cart', function ($query) {
                $query->where('session_id', session()->getId());
            })
            ->first();

        // If the cart item exists, delete it
        if ($cartItem) {
            $cartItem->delete();
            return back()->with('success', 'Item removed from cart');
        } else {
            return back()->with('error', 'Item not found in the cart');
        }
    }

    public function applyDiscount(Request $request)
    {
        // Validate the discount input as a numeric value
        $request->validate([
            'discount' => 'required|numeric|min:0', // Accept decimal values
        ]);

        // Retrieve the user's cart
        $cart = Cart::where('user_id', Auth::id())->with('cartItems')->first();

        if (!$cart || $cart->cartItems->isEmpty()) {
            return back()->with('error', 'Your cart is empty. Please add items before applying a discount.');
        }
        // Calculate the total amount from the cart items
        $total = $cart->cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        $discount = $request->discount;

        if ($discount > $total) {
            return back()->with('error', 'Discount cannot exceed the total amount.');
        }

        // Calculate the total after applying the discount
        $totalAfterDiscount = $total - $discount;

        // Store the updated total and discount in the session
        session(['total' => $totalAfterDiscount, 'discount' => $discount]);

        return back()->with('success', 'Discount applied successfully.');
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'amount_received' => 'required|numeric|min:0'
        ]);

        $cart_items = CartItem::with('product')
            ->whereHas('cart', function ($query) {
                $query->where('session_id', session()->getId());
            })
            ->get();

        if ($cart_items->isEmpty()) {
            return back()->with('error', 'Cart is empty');
        }

        $subtotal = $cart_items->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        $discount = session('discount', 0);
        $total = $subtotal - $discount;

        if ($request->amount_received < $total) {
            return back()->with('error', 'Insufficient payment amount');
        }

        $sale = null; // Declare $sale outside to use it later

        try {
            DB::transaction(function () use ($cart_items, $total, $discount, $request, &$sale) {
                // Create the Sale entry
                $sale = Sale::create([
                    'user_id' => Auth::id(),
                    'total_amount' => $total,
                    'discount' => $discount,
                    'amount_received' => $request->amount_received,
                    'status' => 'completed'  // Sale completed
                ]);

                // Create SaleDetails and update inventory
                foreach ($cart_items as $item) {
                    SaleDetail::create([
                        'sale_id' => $sale->id,
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity,
                        'amount' => $item->price * $item->quantity // Store total amount for this product
                    ]);

                    // Get the product's associated inventory record
                    $inventory = $item->product->inventory;

                    // Check if an inventory record exists and decrement the quantity
                    if ($inventory && $inventory->quantity >= $item->quantity) {
                        $inventory->decrement('quantity', $item->quantity);
                    } else {
                        throw new \Exception('Not enough stock for ' . $item->product->name);
                    }
                }

                // Clear cart and discount session
                Cart::where('session_id', session()->getId())->delete();
                session()->forget('discount');
            });
        } catch (\Exception $e) {
            return back()->with('error', 'Checkout failed: ' . $e->getMessage());
        }

        // Ensure $sale is not null before accessing it
        if (!$sale) {
            return back()->with('error', 'Failed to complete the sale.');
        }

        $change = $request->amount_received - $total;

        return redirect()->route('pos.receipt', $sale->id)->with([
            'success' => 'Sale completed successfully',
            'change' => $change,
            'sale_id' => $sale->id,
            'sale_items' => $cart_items,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total' => $total,
            'amount_received' => $request->amount_received
        ]);
    }

    public function receipt(Request $request, $sale_id)
    {
        // Retrieve sale details with associated products
        $sale = Sale::with('saleDetails.product')->find($sale_id);

        if (!$sale) {
            return redirect()->route('pos.index')->with('error', 'Sale not found.');
        }

        // Calculate the change if necessary
        $amount_received = $sale->amount_received;
        $total = $sale->total_amount;
        $change = $amount_received - $total;

        return view('pos.receipt', compact('sale', 'change'));
    }
}
