<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'total_amount',
        'discount',
        'amount_received',
        'refunded_amount',
        'status',
        'sale_date',
    ];

    protected $casts = [
        'sale_date' => 'datetime',
    ];

    public function getFormattedTotalAmountAttribute(): string
    {
        return number_format($this->total_amount, 2);
    }

    public function getFormattedDiscountAttribute(): ?string
    {
        return $this->discount ? number_format($this->discount, 2) : null;
    }

    public function getPriceAttribute()
    {
        return $this->attributes['price'];
    }

    public function getSubtotalAttribute()
    {
        return $this->saleDetails->sum(function ($saleDetail) {
            return $saleDetail->product->price * $saleDetail->quantity;
        });
    }


    public function saleDetails(): HasMany
    {
        return $this->hasMany(SaleDetail::class);
    }
}
