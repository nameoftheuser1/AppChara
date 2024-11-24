<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderDetail extends Model
{
    use HasFactory;

    // Define the table name (optional if it's plural of the model name)
    protected $table = 'order_details';

    // Specify the attributes that are mass assignable
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'amount',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Relationship with the Product model.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
