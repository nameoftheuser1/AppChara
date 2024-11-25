<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_key',
        'name',
        'contact_number',
        'coupon',
        'pick_up_date',
        'order_id',
        'status',
        'email',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    protected $casts = [
        'pick_up_date' => 'date',
        'status' => 'string',
    ];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'reservation_product')
            ->withPivot('quantity')
            ->withTimestamps();
    }
}
