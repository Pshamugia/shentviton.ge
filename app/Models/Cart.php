<?php

namespace App\Models;

use App\DTO\DBPaymentData;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "visitor_hash",
        "product_id",
        "quantity",
        "total_price",
        "default_img",
        "design_front_image",
        "design_back_image",
        "payment_id",
    ];


    public function product()
    {
        return $this->belongsTo(\App\Models\ProductColor::class, 'product_id', 'product_id');
    }

    public function baseProduct()
    {
        return $this->belongsTo(\App\Models\Product::class, 'product_id', 'id');
    }

    public static function createDBDTO(array $form_data)
    {
        $user_id = auth()->id();
        $visitor_hash = session('v_hash');

        $cart_items = Cart::where('user_id', $user_id)
            ->orWhere('visitor_hash', $visitor_hash)
            ->get();

        $dto = DBPaymentData::fromCartCollectionAndFormData($cart_items, $form_data);

        return $dto ? $dto : null;
    }

    public function isPaid()
    {
        return $this->payment_id !== null;
    }
}
