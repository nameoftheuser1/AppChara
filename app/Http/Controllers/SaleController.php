<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Http\Requests\StoreSaleRequest;
use App\Http\Requests\UpdateSaleRequest;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('sales.index');
    }

    public function saleIndex(Request $request)
    {
        $salesQuery = Sale::with(['saleDetails.product'])
            ->orderBy('sale_date', 'desc');

        if ($request->has('month')) {
            $month = $request->input('month');
            $salesQuery->whereMonth('sale_date', $month);
        }

        // Use paginate with a desired items-per-page value
        $sales = $salesQuery->paginate(10);

        return view('sales.sales', compact('sales'));
    }

    public function showSaleDetails($saleId)
    {
        $sale = Sale::with(['saleDetails.product'])->findOrFail($saleId);
        return response()->json([
            'id' => $sale->id,
            'sale_date' => $sale->sale_date,
            'formatted_total_amount' => $sale->formatted_total_amount,
            'status' => $sale->status,
            'sale_details' => $sale->saleDetails->map(function ($detail) {
                return [
                    'product' => $detail->product,
                    'quantity' => $detail->quantity,
                    'product_price' => $detail->product->price,
                ];
            }),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sales = Sale::select('sale_date', 'total_amount')
            ->orderBy('sale_date', 'desc')
            ->paginate(10);

        return view('sales.create', compact('sales'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate incoming data
        $validatedData = $request->validate([
            'sale_date' => ['required', 'date'],
            'total_amount' => ['required', 'numeric', 'min:0'],
        ]);

        try {
            // Create a new sale record
            Sale::create([
                'sale_date' => $validatedData['sale_date'],
                'amount_received' => $validatedData['total_amount'],
                'total_amount' => $validatedData['total_amount'],
            ]);

            // Redirect with success message
            return redirect()->back()->with('success', 'Sale successfully recorded.');
        } catch (\Exception $e) {
            // Redirect back with error message if something goes wrong
            return redirect()->back()->with('error', 'An error occurred while recording the sale. Please try again.');
        }
    }

    public function processRefund($saleId)
    {
        $sale = Sale::findOrFail($saleId);

        // Update refunded_amount
        $sale->refunded_amount = $sale->total_amount;
        $sale->total_amount = 0;
        $sale->status = 'refunded';  // You may want to set the status to 'refunded' or similar
        $sale->save();

        // Loop through sale details to return products to inventory
        foreach ($sale->saleDetails as $saleDetail) {
            $product = $saleDetail->product;
            $product->inventory->increment('quantity', $saleDetail->quantity); // Return quantity to inventory
        }

        return redirect()->back()->with('success', 'Sale successfully refunded.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sale $sale)
    {
        //
    }
}
