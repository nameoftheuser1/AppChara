<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('homepage.index');
    }

    public function checkStatus(Request $request)
    {
        $request->validate([
            'transaction_key' => 'required|string',
        ]);

        $order = Order::with(['orderDetails.product', 'reservation'])
            ->where('transaction_key', $request->transaction_key)
            ->first();

        if (!$order) {
            return back()->with('error', 'Transaction not found. Please check your transaction key.');
        }

        return view('homepage.check-status', compact('order'));
    }

    public function showCheckStatusForm()
    {
        return view('homepage.check-status');
    }
}
