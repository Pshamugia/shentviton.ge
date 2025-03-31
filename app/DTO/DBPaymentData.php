<?php

namespace App\DTO;

class DBPaymentData
{
    public function __construct(
        public ?int $user_id,
        public ?string $visitor_hash,
        public float $amount,
        public string $cart_ids,
        public ?string $u_name,
        public ?string $u_email,
        public ?string $u_phone,
        public ?string $u_address,
    ) {}

    public static function fromCartCollectionandFormData($cart_items, $form_data): self
    {
        $total = $cart_items->sum('total_price');

        return new self(
            user_id: $cart_items->first()->user_id,
            visitor_hash: $cart_items->first()->visitor_hash,
            amount: $total,
            cart_ids: json_encode($cart_items->pluck('id')->toArray()),
            u_name: $form_data['name'],
            u_email: $form_data['email'],
            u_phone: $form_data['phone'],
            u_address: $form_data['address'],
        );
    }
}
