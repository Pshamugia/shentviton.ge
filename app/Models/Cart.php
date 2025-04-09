<?php

namespace App\Models;

use App\DTO\DBPaymentData;
use App\Enums\CartStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        "front_assets",
        "back_assets",
        "status",
        "payment_id",
    ];

    protected $casts = [
        "status"=> CartStatus::class,
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

        $cart_items = Cart::where(function ($query) use ($user_id, $visitor_hash) {
            $query->where('user_id', $user_id)
                ->orWhere('visitor_hash', $visitor_hash);
        })
            ->where('status', CartStatus::PENDING)
            ->get();

        $dto = DBPaymentData::fromCartCollectionAndFormData($cart_items, $form_data);

        return $dto ?: null;
    }


    public function isPaid()
    {
        return $this->payment_id !== null;
    }
}