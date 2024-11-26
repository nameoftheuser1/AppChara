<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class StatusUpdateController extends Controller
{
    public function process(Order $order)
    {
        $order->update(['status' => 'processing']);
        return redirect()->back()->with('success', 'Order has been moved to processing.');
    }

    public function readyToPickUp(Order $order)
    {
        $order->update(['status' => 'ready to pickup']);
        return redirect()->back()->with('success', 'Order has been moved to ready to pickup.');
    }

    public function complete(Order $order)
    {
        $order->update(['status' => 'completed']);
        return redirect()->back()->with('success', 'Order has been moved to completed.');
    }
}
