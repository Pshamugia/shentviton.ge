<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\ProductColor; // ✅ Import ProductColor model

class Product extends Model
{
    protected $fillable = [
        'title', 'description', 'full_text', 'image1', 'image2', 'image3', 'image4',
        'size', 'quantity', 'price'
    ];

    public function colors(): HasMany
    {
        return $this->hasMany(ProductColor::class, 'product_id', 'id'); // ✅ Ensure correct foreign key
    }
}