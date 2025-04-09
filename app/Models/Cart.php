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
        "status" => CartStatus::class,
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

        $cartQuery = Cart::query();

        if ($user_id) {
            $cartQuery->where(function ($subquery) use ($user_id, $visitor_hash) {
                $subquery->where('user_id', $user_id);

                if ($visitor_hash) {
                    $subquery->orWhere('visitor_hash', $visitor_hash);
                }
            });
        } elseif ($visitor_hash) {
            $cartQuery->where('visitor_hash', $visitor_hash);
        } else {
            $cartQuery->where('id', 0);
        }

        $cart_items = $cartQuery
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
