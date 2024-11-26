<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Http\Requests\StoreInventoryRequest;
use App\Http\Requests\UpdateInventoryRequest;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\SaleDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get stocks per product (including quantity)
        $stocksPerProduct = Product::with('inventory')
            ->get()
            ->map(function ($product) {
                return [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'stock_quantity' => $product->inventory ? $product->inventory->quantity : 0,
                    'stock_value' => $product->inventory ? $product->inventory->quantity * $product->price : 0,
                ];
            })
            ->toArray();

        $stocks = $this->getStocksPerProduct();

        // Get sales for each product over the last three months
        $sales = $this->getSalesPerProductLastThreeMonths();

        // Calculate total stock value
        $totalStockValue = array_sum(array_column($stocks, 'stock_value'));

        $products = collect($stocks)->map(function ($stock) use ($sales) {
            // Find matching sales data for this product
            $sale = collect($sales)->firstWhere('product_id', $stock['product_id']);

            // If no sales data, default to 0
            $totalSales = $sale ? $sale['total_sales'] : 0;

            return [
                'product_id' => $stock['product_id'],
                'product_name' => $stock['product_name'],
                'stock_value' => $stock['stock_value'],
                'total_sales' => $totalSales,
            ];
        })->values();

        // Get stock and sales comparison

        return view('inventories.index', [
            'stocks' => $stocksPerProduct,
            'products' => $products,
            'totalStockValue' => $totalStockValue,
        ]);
    }

    /**
     * Calculate stock value and monthly sales for products.
     *
     * @return array
     */

    private function getStocksPerProduct(): array
    {
        return Product::with('inventory')
            ->get()
            ->map(function ($product) {
                return [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'stock_value' => $product->inventory ? $product->inventory->quantity * $product->price : 0,
                ];
            })
            ->toArray();
    }


    private function getSalesPerProductLastThreeMonths(): array
    {
        // Define the date range for the past 3 months
        $startDate = now()->subMonths(3)->startOfMonth();
        $endDate = now()->endOfMonth();

        // Get sales from SaleDetail for the last 3 months
        $saleDetails = SaleDetail::select('product_id', DB::raw('SUM(amount) as total_sales'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('product_id')
            ->get()
            ->keyBy('product_id')
            ->map(fn($sale) => [
                'product_id' => $sale->product_id,
                'total_sales' => (float)$sale->total_sales,
            ]);

        // Get sales from OrderDetail for completed orders in the last 3 months
        $orderDetails = OrderDetail::select('product_id', DB::raw('SUM(amount) as total_sales'))
            ->whereHas('order', function ($query) use ($startDate, $endDate) {
                $query->where('status', Order::STATUS_COMPLETED)
                    ->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->groupBy('product_id')
            ->get()
            ->keyBy('product_id')
            ->map(fn($order) => [
                'product_id' => $order->product_id,
                'total_sales' => (float)$order->total_sales,
            ]);

        // Combine and sum sales explicitly
        $combinedSales = $saleDetails->union($orderDetails)->map(function ($sales, $productId) use ($saleDetails, $orderDetails) {
            $saleTotal = $saleDetails->get($productId)['total_sales'] ?? 0;
            $orderTotal = $orderDetails->get($productId)['total_sales'] ?? 0;

            return [
                'product_id' => $productId,
                'total_sales' => $saleTotal + $orderTotal,
            ];
        });

        return $combinedSales->values()->toArray();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Inventory $inventory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return view('inventories.edit', [
            'product' => $product,
            'inventory' => $product->inventory
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:0',
        ]);

        $inventory = $product->inventory;
        $inventory->quantity = $request->quantity;
        $inventory->save();

        return redirect()->route('products.index')->with('success', 'Inventory updated successfully.');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Inventory $inventory)
    {
        //
    }
}
