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

        $sales = $salesQuery->get();
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
        //
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
    public function show(Sale $sale)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sale $sale)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sale $sale)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sale $sale)
    {
        //
    }
}
