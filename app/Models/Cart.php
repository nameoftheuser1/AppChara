<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'total_amount', 'session_id'];

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class, 'cart_id');
    }

    // Method to update the total amount of the cart
    public function updateTotalAmount()
    {
        $totalAmount = $this->cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        $this->total_amount = $totalAmount;
        $this->save();
    }
}
