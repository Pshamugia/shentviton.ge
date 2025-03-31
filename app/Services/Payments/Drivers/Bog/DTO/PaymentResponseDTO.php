<?php

namespace App\Services\Payments\Drivers\Bog\DTO;

final class PaymentResponseDTO
{
    public function __construct(
        private readonly mixed $id,
        private readonly ?string $redirect_url = null,
        private readonly ?string $details_url = null
    ) {}

    public static function fromArray(array $data): self
    {
        $links = $data['_links'];

        return new self(
            id: $data['id'] ?? null,
            redirect_url: $links['redirect']['href'] ?? null,
            details_url: $links['details']['href'] ?? null
        );
    }

    public function getId(): mixed
    {
        return $this->id;
    }

    public function getOrderID(): mixed
    {
        $query = parse_url($this->redirect_url, PHP_URL_QUERY);
        parse_str($query, $params);

        $order_id = $params['order_id'] ?? null;

        return $order_id;
    }

    public function getRedirectUrl(): ?string
    {
        return $this->redirect_url;
    }

    public function getDetailsUrl(): ?string
    {
        return $this->details_url;
    }
}
