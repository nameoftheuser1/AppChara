<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Sale;
use Illuminate\Http\Request;

class ReservationPosController extends Controller
{
    public function index(Request $request)
    {
        // Apply filters for the month
        $month = $request->query('month');
        $ordersQuery = Order::query();
        $salesQuery = Sale::query();

        if ($month) {
            $ordersQuery->whereMonth('created_at', $month);
            $salesQuery->whereMonth('created_at', $month);
        }

        // Paginate results
        $orders = $ordersQuery->select('total_amount', 'status', 'created_at')->paginate(10);
        $sales = $salesQuery->select('total_amount', 'status', 'created_at')->paginate(10);

        return view('sales.reservation-sales', [
            'orders' => $orders,
            'sales' => $sales,
        ]);
    }
}
