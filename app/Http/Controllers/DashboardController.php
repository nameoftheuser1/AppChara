<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Fetch sales data for the last 12 months
        $salesData = $this->getSalesData();

        // Get predictions for the next three months
        $predictions = $this->predictNextThreeMonths($salesData);

        // Pass sales data and predictions to the view
        return view('dashboard.index', [
            'salesData' => $salesData,
            'predictions' => $predictions,
        ]);
    }

    private function getSalesData()
    {
        return DB::table('sales')
            ->selectRaw('YEAR(sale_date) as year, MONTH(sale_date) as month, SUM(total_amount) as total_amount')
            ->groupBy('year', 'month')
            ->orderByRaw('year, month')
            ->get();
    }

    private function predictNextThreeMonths($salesData)
    {
        // Extract the total amounts from the sales data
        $salesTotals = $salesData->pluck('total_amount')->toArray();

        // Calculate the 3-month moving average
        $lastThreeMonths = array_slice($salesTotals, -3); // Last 3 months
        $lastThreeMonthAverage = array_sum($lastThreeMonths) / max(count($lastThreeMonths), 1);

        // Predict the next three months with the moving average
        return array_fill(0, 3, $lastThreeMonthAverage);
    }
}
