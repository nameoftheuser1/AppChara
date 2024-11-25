<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservationProduct extends Model
{
    use HasFactory;

    protected $table = 'reservation_product';

    protected $fillable = [
        'reservation_id',
        'product_id',
        'quantity',
    ];
}
