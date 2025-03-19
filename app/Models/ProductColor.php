<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class ProductColor extends Model
{
    protected $fillable = [
        'product_id', 'color_name', 'color_code', 'front_image', 'back_image'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id'); // ✅ Ensure correct foreign key
    }
}
