<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'total_amount',
        'discount',
        'sale_date',
    ];

    public function getFormattedTotalAmountAttribute(): string
    {
        return number_format($this->total_amount, 2);
    }

    public function getFormattedDiscountAttribute(): ?string
    {
        return $this->discount ? number_format($this->discount, 2) : null;
    }
}
