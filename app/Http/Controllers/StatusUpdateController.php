<?php

namespace App\Http\Controllers;

use App\Mail\OrderCancelled;
use App\Models\Inventory;
use App\Models\Order;
use App\Notifications\ReservationStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class StatusUpdateController extends Controller
{
    public function process(Order $order)
    {
        $order->update(['status' => 'processing']);

        if ($order->reservation) {
            $order->reservation->notify(new ReservationStatusUpdated($order->reservation));
        }

        return redirect()->back()->with('success', 'Order has been moved to processing.');
    }

    public function cancel(Order $order)
    {
        $order->update(['status' => 'cancelled']);

        if ($order->reservation) {
            $order->reservation->notify(new ReservationStatusUpdated($order->reservation));
        }

        return redirect()->back()->with('success', 'Order has been moved to cancelled.');
    }

    public function readyToPickUp(Order $order)
    {
        $order->update(['status' => 'ready to pickup']);

        if ($order->reservation) {
            $order->reservation->notify(new ReservationStatusUpdated($order->reservation));
        }

        return redirect()->back()->with('success', 'Order has been moved to ready to pickup.');
    }

    public function complete(Order $order)
    {
        // Begin a transaction to ensure data consistency
        DB::beginTransaction();

        try {
            // Update the order status to completed
            $order->update(['status' => 'completed']);

            // Loop through the order details to check inventory and deduct quantities
            foreach ($order->orderDetails as $orderDetail) {
                // Get the product related to the order detail
                $product = $orderDetail->product;

                // Check if the product has an inventory record
                if ($product->inventory) {
                    // Check if there is enough stock to fulfill the order
                    if ($product->inventory->quantity < $orderDetail->quantity) {
                        throw new \Exception("Not enough stock for product: {$product->name}. Required: {$orderDetail->quantity}, Available: {$product->inventory->quantity}");
                    }

                    // Subtract the quantity from the inventory
                    $product->inventory->decrement('quantity', $orderDetail->quantity);
                } else {
                    // Optionally, handle case if inventory record does not exist
                    throw new \Exception("Inventory record not found for product: {$product->name}");
                }
            }

            // Commit the transaction if no errors occurred
            DB::commit();

            // Find the reservation linked to the order
            $reservation = $order->reservation;

            // Notify the user about the status update
            $reservation->notify(new ReservationStatusUpdated($reservation));

            return redirect()->back()->with('success', 'Order has been moved to completed and inventory updated.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Error updating order: ' . $e->getMessage());
        }
    }

    public function cancelOrder($transactionKey)
    {
        // Find the order by the transaction key
        $order = Order::where('transaction_key', $transactionKey)->first();

        if (!$order) {
            return redirect()->back()->with('error', 'Order not found.');
        }

        if ($order->status !== 'pending') {
            return redirect()->back()->with('error', 'You can only cancel pending orders.');
        }

        $order->status = 'cancelled';
        $order->save();

        $ownerEmail = DB::table('settings')->where('key', 'email')->value('value') ?? 'appchara12@gmail.com';

        Mail::to($ownerEmail)->send(new OrderCancelled($order));

        return redirect()->route('check.status.form')->with('success', 'Order has been cancelled successfully.');
    }

    public function refund($id)
    {
        $order = Order::findOrFail($id);

        // Calculate the refunded amount (sum of all order details amounts)
        $refundedAmount = 0;
        foreach ($order->orderDetails as $detail) {
            $inventory = Inventory::where('product_id', $detail->product_id)->first();
            if ($inventory) {
                $inventory->quantity += $detail->quantity;
                $inventory->save();
            }
            $refundedAmount += $detail->quantity * $detail->price;
        }

        if ($order->reservation) {
            // Update the refunded amount in the reservation
            $order->reservation->update([
                'status' => 'refunded',
                'refunded_amount' => $refundedAmount,
            ]);

            // Notify the reservation status update
            $order->reservation->notify(new ReservationStatusUpdated($order->reservation));
        }

        $order->update([
            'status' => 'refunded',
            'total_amount' => 0,
        ]);

        return redirect()->route('reservations.complete')->with('success', 'Order refunded successfully.');
    }
}
