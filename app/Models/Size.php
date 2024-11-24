<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Size extends Model
{
    use HasFactory;

    protected $fillable = [
        'size',
    ];

    /**
     * Get the products associated with the size.
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_size')
            ->withPivot('price')  // Include price in the pivot table
            ->withTimestamps();
    }
}
