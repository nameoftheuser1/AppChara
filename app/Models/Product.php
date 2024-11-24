<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'price',
        'img_path',
    ];

    /**
     * Get the price formatted with commas.
     *
     * @return string
     */
    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->price, 2);
    }

    /**
     * Get the inventory record associated with the product.
     */
    public function inventory(): HasOne
    {
        return $this->hasOne(Inventory::class);
    }

}
