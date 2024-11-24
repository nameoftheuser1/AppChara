<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        $cart_items = CartItem::with('product')
            ->where('session_id', session()->getId())
            ->get();

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

        if ($product->inventory_count < $request->quantity) {
            return back()->with('error', 'Insufficient stock');
        }

        CartItem::create([
            'session_id' => session()->getId(),
            'product_id' => $product->id,
            'quantity' => $request->quantity,
            'price' => $product->price
        ]);

        return back()->with('success', 'Item added to cart');
    }

    public function removeItem(Request $request)
    {
        CartItem::where('id', $request->item_id)
            ->where('session_id', session()->getId())
            ->delete();

        return back()->with('success', 'Item removed from cart');
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

        DB::transaction(function () use ($cart_items, $total, $discount) {
            $sale = Sale::create([
                'user_id' => auth()->id(),
                'total_amount' => $total,
                'discount_percentage' => $discount
            ]);

            foreach ($cart_items as $item) {
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price
                ]);

                // Update inventory
                $item->product->decrement('inventory_count', $item->quantity);
            }

            // Clear cart
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
