<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = ['user_id', 'total_amount', 'session_id'];

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    // Method to update the total amount of the cart
    public function updateTotalAmount()
    {
        $totalAmount = $this->items->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        $this->total_amount = $totalAmount;
        $this->save();
    }
}
