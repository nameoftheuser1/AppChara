<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    /** @use HasFactory<\Database\Factories\CouponFactory> */
    use HasFactory;

    // Define the table name (if it's different from the plural of the model name)
    protected $table = 'coupons';

    // Specify the attributes that are mass assignable
    protected $fillable = [
        'code',
        'status',
        'discount_percent',
    ];
}
