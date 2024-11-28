<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Phpml\Exception\InvalidArgumentException;
use Phpml\FeatureExtraction\TokensFilter;
use Phpml\Regression\LeastSquares;
use Phpml\Regression\SVR;
use Phpml\Regression\LassoRegression;

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

        // Get fast moving products
        $fastMovingProducts = $this->getFastMovingProducts();

        // Pass the prepared data to the view
        return view('dashboard.index', [
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
            ->groupBy(DB::raw('DATE(sale_date)'))
            ->orderBy('date')
            ->get();
    }

    private function predictNextThreeMonths($historicalData)
    {
        // Validate and prepare data
        if ($historicalData->isEmpty()) {
            return $this->simpleFallbackPrediction($historicalData);
        }

        // Prepare data for regression
        $samples = [];
        $targets = [];

        $historicalData->each(function ($item, $index) use (&$samples, &$targets) {
            // Use index as feature
            $samples[] = [$index + 1];
            $targets[] = (float)$item->total_amount;
        });

        try {
            // Create and train the regression model
            $regression = new LeastSquares();
            $regression->train($samples, $targets);

            // Predict next 90 days
            $predictions = [];
            $lastDate = Carbon::parse($historicalData->last()->date);

            for ($i = 1; $i <= 90; $i++) {
                $predictedDate = $lastDate->copy()->addDays($i);

                // Predict using the next sequential index
                $samplesCount = count($samples);
                $predictedAmount = max(0, $regression->predict([$samplesCount + $i]));

                $predictions[] = [
                    'year' => $predictedDate->year,
                    'month' => $predictedDate->month,
                    'day' => $predictedDate->day,
                    'date' => $predictedDate->toDateString(),
                    'total_amount' => $predictedAmount,
                ];
            }

            return collect($predictions);
        } catch (InvalidArgumentException $e) {
            // Fallback if regression fails
            return $this->simpleFallbackPrediction($historicalData);
        }
    }

    private function simpleFallbackPrediction($historicalData)
    {
        $averageAmount = $historicalData->isEmpty()
            ? 0
            : $historicalData->avg('total_amount');

        $lastDate = Carbon::parse($historicalData->last()->date);
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
