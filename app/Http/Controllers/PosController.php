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
        $total = $subtotal * (1 - ($discount / 100));

        return view('pos.index', compact('products', 'cart_items', 'subtotal', 'total', 'discount'));
    }

    public function addItem(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($request->product_id);

        // Check if sufficient stock is available
        if ($product->inventory->quantity < $request->quantity) {
            return back()->with('error', 'Insufficient stock');
        }

        // Find or create the current session's cart
        $cart = Cart::firstOrCreate(['session_id' => session()->getId()]);

        // Add the item to the cart
        $cartItem = CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => $request->quantity,
            'price' => $product->price
        ]);

        return back()->with('success', 'Item added to cart');
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
        $request->validate([
            'discount' => 'required|numeric|min:0|max:100'
        ]);

        session(['discount' => $request->discount]);

        return back()->with('success', 'Discount applied');
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'amount_received' => 'required|numeric|min:0'
        ]);

        $cart_items = CartItem::with('product')
            ->where('session_id', session()->getId())
            ->get();

        if ($cart_items->isEmpty()) {
            return back()->with('error', 'Cart is empty');
        }

        $subtotal = $cart_items->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        $discount = session('discount', 0);
        $total = $subtotal * (1 - ($discount / 100));

        if ($request->amount_received < $total) {
            return back()->with('error', 'Insufficient payment amount');
        }

        DB::transaction(function () use ($cart_items, $total, $discount, $request) {
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

                // Update inventory
                $item->product->decrement('inventory_count', $item->quantity);
            }

            // Clear cart and discount session
            CartItem::where('session_id', session()->getId())->delete();
            session()->forget('discount');
        });

        $change = $request->amount_received - $total;

        return redirect()->route('pos.receipt')->with([
            'success' => 'Sale completed successfully',
            'change' => $change
        ]);
    }
}
