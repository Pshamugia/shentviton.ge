<?php

namespace App\DTO;

use App\Models\Cart;

class PaymentRequestDTO
{
    public function __construct(
        public string $payment_id,
        public float $total_amount,
        public array $basket,
    ) {}

    public static function fromPaymentData($payment): self
    {
        $basket = [];

        foreach ($payment->cart_ids as $cart_id) {
            $cart_item = Cart::find($cart_id);
            if ($cart_item) {
                $basket[] = [
                    'quantity' => $cart_item->quantity,
                    'unit_price' => optional($cart_item->baseProduct)->price,
                    'product_id' => $cart_item->product_id,
                ];
            }
        }

        return new self(
            payment_id: (string)$payment->id,
            total_amount: $payment->amount,
            basket: $basket
        );
    }
}
