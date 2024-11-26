<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Fetch historical data for the last 5 months
        $historicalData = $this->getHistoricalData();

        // Predict the next three months
        $predictions = $this->predictNextThreeMonths($historicalData);

        // Combine historical data and predictions for the chart
        $chartData = $this->prepareChartData($historicalData, $predictions);

        // Prepare the categories and data in the format ApexCharts expects
        $categories = $chartData->pluck('month')->toArray();
        $data = $chartData->pluck('total_amount')->toArray();

        // Pass the prepared data to the view
        return view('dashboard.index', [
            'historicalData' => $historicalData,
            'categories' => $categories,
            'data' => $data,
        ]);
    }

    private function getHistoricalData()
    {
        // Combine sales and orders data
        return DB::table('sales')
            ->selectRaw('
                YEAR(sale_date) as year,
                MONTH(sale_date) as month,
                SUM(sales.total_amount) as total_sale_amount,
                COALESCE(SUM(orders.total_amount), 0) as total_order_amount,
                (SUM(sales.total_amount) + COALESCE(SUM(orders.total_amount), 0)) as total_amount') // Combined total amount
            ->leftJoin('orders', function ($join) {
                $join->on(DB::raw('YEAR(orders.created_at)'), '=', DB::raw('YEAR(sales.sale_date)'))
                    ->on(DB::raw('MONTH(orders.created_at)'), '=', DB::raw('MONTH(sales.sale_date)'))
                    ->where('orders.status', 'completed');
            })
            ->whereRaw('sale_date >= DATE_SUB(CURDATE(), INTERVAL 5 MONTH)')
            ->groupBy(DB::raw('YEAR(sale_date), MONTH(sale_date)'))
            ->orderByRaw('year, month')
            ->get();
    }


    private function predictNextThreeMonths($historicalData)
    {
        // Extract the total amounts from the historical data
        $salesTotals = $historicalData->pluck('total_amount')->toArray();

        // Calculate the 3-month moving average
        $lastThreeMonths = array_slice($salesTotals, -3); // Last 3 months
        $lastThreeMonthAverage = array_sum($lastThreeMonths) / max(count($lastThreeMonths), 1);

        // Predict the next three months with the moving average
        $currentYear = now()->year;
        $currentMonth = now()->month;

        $predictions = [];
        for ($i = 1; $i <= 3; $i++) {
            $nextMonth = $currentMonth + $i;
            $year = $currentYear + floor(($nextMonth - 1) / 12);
            $month = (($nextMonth - 1) % 12) + 1;

            $predictions[] = [
                'year' => $year,
                'month' => $month,
                'total_amount' => $lastThreeMonthAverage,
            ];
        }

        return collect($predictions);
    }

    private function prepareChartData($historicalData, $predictions)
    {
        $chartData = $historicalData->map(function ($item) {
            return [
                'month' => sprintf('%s-%02d', $item->year, $item->month),
                'total_amount' => $item->total_amount,
            ];
        });

        $predictedData = $predictions->map(function ($item) {
            return [
                'month' => sprintf('%s-%02d', $item['year'], $item['month']),
                'total_amount' => $item['total_amount'],
            ];
        });

        return $chartData->merge($predictedData);
    }
}
