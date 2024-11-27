<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Notifications\ReservationStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
}
