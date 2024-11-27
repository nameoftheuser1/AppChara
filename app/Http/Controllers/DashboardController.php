<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        // Prepare data for regression
        $samples = [];
        $targets = [];

        // Validate and prepare data
        if ($historicalData->isEmpty()) {
            return collect([]);
        }

        // Create numeric representation of months with multiple features
        $historicalData->each(function ($item, $index) use (&$samples, &$targets) {
            $samples[] = [
                $index + 1,                     // Sequence index
                (float)$item->total_amount,     // Previous total amount
                (int)$item->month,              // Month as feature
            ];
            $targets[] = $item->total_amount;
        });

        // Try multiple regression techniques
        $regressionMethods = [
            new LeastSquares(),
            new SVR()
        ];

        $predictions = [];
        $currentYear = now()->year;
        $currentMonth = now()->month;

        foreach ($regressionMethods as $regression) {
            try {
                // Train the regression model
                $regression->train($samples, $targets);

                // Predict next three months
                for ($i = 1; $i <= 3; $i++) {
                    $nextMonth = $currentMonth + $i;
                    $year = $currentYear + floor(($nextMonth - 1) / 12);
                    $month = (($nextMonth - 1) % 12) + 1;

                    // Predict using multiple features
                    $predictedAmount = $regression->predict([
                        count($samples) + $i,
                        $targets[count($targets) - 1],
                        $month
                    ]);

                    $predictions[] = [
                        'year' => $year,
                        'month' => $month,
                        'total_amount' => max(0, $predictedAmount),
                    ];
                }

                return collect($predictions);
            } catch (\Exception $e) {
                continue;
            }
        }

        return $this->simpleFallbackPrediction($historicalData);
    }


    private function simpleFallbackPrediction($historicalData)
    {
        $averageAmount = $historicalData->avg('total_amount');

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
