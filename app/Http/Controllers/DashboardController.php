<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Phpml\Exception\InvalidArgumentException;
use Phpml\Regression\LeastSquares;

class DashboardController extends Controller
{
    public function index()
    {
        // Fetch historical data for the last 5 months
        $historicalData = $this->getHistoricalData();

        // Group data by year and month and sum the total_amount
        $groupedData = collect($historicalData)
            ->groupBy(function ($item) {
                return $item->year . '-' . $item->month; // Group by year and month
            })
            ->map(function ($items, $key) {
                $totalAmount = $items->sum('total_amount'); // Sum total_amount for the group
                $firstItem = $items->first(); // Use the first item for metadata
                return (object) [
                    'year' => $firstItem->year,
                    'month' => $firstItem->month,
                    'total_amount' => $totalAmount,
                ];
            })->values(); // Reset the keys


        // Predict the next three months
        $predictions = $this->predictNextThreeMonths($historicalData);

        // Combine historical data and predictions for the chart
        $chartData = $this->prepareChartData($historicalData, $predictions);

        // Prepare the categories and data in the format ApexCharts expects
        $categories = $chartData->pluck('month')->toArray();
        $data = $chartData->pluck('total_amount')->toArray();

        // Get fast moving products
        $fastMovingProducts = $this->getFastMovingProducts();

        // Pass the prepared data to the view
        return view('dashboard.index', [
            'groupedData' => $groupedData,
            'historicalData' => $historicalData,
            'categories' => $categories,
            'data' => $data,
            'fastMovingProducts' => $fastMovingProducts,
        ]);
    }

    private function getHistoricalData()
    {
        // Fetch daily sales and orders data
        return DB::table('sales')
            ->selectRaw('
            DATE(sale_date) as date,
            YEAR(sale_date) as year,
            MONTH(sale_date) as month,
            DAY(sale_date) as day,
            SUM(sales.total_amount) as total_sale_amount,
            COALESCE(SUM(orders.total_amount), 0) as total_order_amount,
            (SUM(sales.total_amount) + COALESCE(SUM(orders.total_amount), 0)) as total_amount') // Combined total amount
            ->leftJoin('orders', function ($join) {
                $join->on(DB::raw('DATE(orders.created_at)'), '=', DB::raw('DATE(sales.sale_date)'))
                    ->where('orders.status', 'completed');
            })
            ->whereRaw('sale_date >= DATE_SUB(CURDATE(), INTERVAL 150 DAY)') // Extended historical data range
            ->groupBy('date', 'year', 'month', 'day')
            ->orderBy('date')
            ->get();
    }

    private function predictNextThreeMonths($historicalData)
    {
        // Group historical data by month to smooth out daily fluctuations
        $monthlyData = $historicalData->groupBy(function ($item) {
            return $item->year . '-' . sprintf('%02d', $item->month);
        })->map(function ($monthGroup) {
            return [
                'total_amount' => $monthGroup->sum('total_amount'),
                'year' => $monthGroup->first()->year,
                'month' => $monthGroup->first()->month
            ];
        })->values();

        // If not enough monthly data, use fallback
        if ($monthlyData->count() < 3) {
            return $this->simpleFallbackPrediction($historicalData);
        }

        // Prepare monthly samples and targets
        $samples = $monthlyData->map(function ($item, $index) {
            return [$index + 1];
        })->toArray();

        $targets = $monthlyData->pluck('total_amount')->toArray();

        try {
            $regression = new LeastSquares();
            $regression->train($samples, $targets);

            $lastMonthData = $monthlyData->last();
            $lastDate = Carbon::create($lastMonthData['year'], $lastMonthData['month'], 1);

            $predictions = [];
            for ($i = 1; $i <= 3; $i++) {
                $predictedDate = $lastDate->copy()->addMonths($i);
                $predictedAmount = max(0, $regression->predict([count($samples) + $i]));

                $predictions[] = [
                    'year' => $predictedDate->year,
                    'month' => $predictedDate->month,
                    'total_amount' => $predictedAmount,
                ];
            }

            return collect($predictions);
        } catch (InvalidArgumentException $e) {
            return $this->simpleFallbackPrediction($historicalData);
        }
    }

    private function simpleFallbackPrediction($historicalData)
    {
        // Check if the collection is empty before trying to calculate the average
        if ($historicalData->isEmpty()) {
            return collect([]); // If empty, return an empty collection
        }

        // Calculate the average total amount
        $averageAmount = $historicalData->avg('total_amount');

        // Get the last record's date
        $lastItem = $historicalData->last();
        $lastDate = Carbon::parse($lastItem->date);

        // Initialize predictions array
        $predictions = [];

        for ($i = 1; $i <= 90; $i++) {
            $predictedDate = $lastDate->copy()->addDays($i);

            $predictions[] = [
                'year' => $predictedDate->year,
                'month' => $predictedDate->month,
                'day' => $predictedDate->day,
                'date' => $predictedDate->toDateString(),
                'total_amount' => $averageAmount,
            ];
        }

        return collect($predictions);
    }

    private function prepareChartData($historicalData, $predictions)
    {
        // Aggregate historical data by month
        $chartData = $historicalData->groupBy(function ($item) {
            return sprintf('%s-%02d', $item->year, $item->month);
        })->map(function ($monthData) {
            return [
                'month' => $monthData->first()->year . '-' . sprintf('%02d', $monthData->first()->month),
                'total_amount' => $monthData->sum('total_amount'),
            ];
        });

        // Aggregate predicted data by month
        $predictedData = $predictions->groupBy(function ($item) {
            return sprintf('%s-%02d', $item['year'], $item['month']);
        })->map(function ($monthData) {
            return [
                'month' => $monthData[0]['year'] . '-' . sprintf('%02d', $monthData[0]['month']),
                'total_amount' => $monthData->sum('total_amount'),
            ];
        });

        // Merge and sort the data
        return $chartData->merge($predictedData)->sortKeys();
    }

    private function getFastMovingProducts()
    {
        // Calculate total quantity sold and ordered for each product in the last 3 months
        $fastMovingProducts = DB::table('products')
            ->select(
                'products.id',
                'products.name',
                DB::raw('COALESCE(SUM(sale_details.quantity), 0) as total_sales_quantity'),
                DB::raw('COALESCE(SUM(order_details.quantity), 0) as total_order_quantity'),
                DB::raw('COALESCE(SUM(sale_details.quantity), 0) + COALESCE(SUM(order_details.quantity), 0) as total_quantity')
            )
            ->leftJoin('sale_details', function ($join) {
                $join->on('sale_details.product_id', '=', 'products.id')
                    ->whereRaw('sale_details.created_at >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)');
            })
            ->leftJoin('order_details', function ($join) {
                $join->on('order_details.product_id', '=', 'products.id')
                    ->whereRaw('order_details.created_at >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)');
            })
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_quantity', 'desc')
            ->limit(10) // Top 10 fast-moving products
            ->get();

        return $fastMovingProducts;
    }
}
