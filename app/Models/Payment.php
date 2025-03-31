<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "visitor_hash",
        "amount",
        "cart_ids",
        "currency",
        "status",
        "gateway",
        "gateway_transaction_id",
        "u_name",
        "u_email",
        "u_phone",
        "u_address",
        "is_parent_order",
    ];


    protected $casts = [
        'cart_ids' => 'array',
    ];

    public function carts()
    {
        return Cart::whereIn('id', $this->cart_ids ?? [])->get();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
