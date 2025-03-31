<?php

namespace App\Services\Payments;

use App\DTO\DBPaymentData;
use App\Models\Payment;
use App\Repositories\PaymentRepository;
use App\DTO\PaymentRequestDTO;
use Illuminate\Support\Facades\Log;
use Exception;

class PaymentProcessor
{
    private PaymentRepository $repository;
    private DBPaymentData $cart_dto;
    private int $payment_id;
    private Payment $payment;
    private PaymentRequestDTO $payment_dto;
    private $response_dto;
    private $parent_order_id;

    public function __construct(PaymentRepository $repository)
    {
        $this->repository = $repository;
    }

    public function withCartData(DBPaymentData $cart_dto): self
    {
        $this->cart_dto = $cart_dto;
        return $this;
    }

    public function createPaymentRecord(): self
    {
        $this->payment_id = $this->repository->createDBRecord($this->cart_dto);

        if (!$this->payment_id) {
            Log::error('Failed to create payment record.', [
                'dto_data' => $this->cart_dto,
            ]);

            throw new Exception('Failed to create payment record.');
        }

        return $this;
    }

    public function findPayment(): self
    {
        $this->payment = Payment::find($this->payment_id);

        if (!$this->payment) {
            Log::error('Failed to find payment record.', [
                'payment_id' => $this->payment_id,
            ]);

            throw new Exception('Failed to find payment record.');
        }

        return $this;
    }

    public function createPaymentDTO(): self
    {
        $this->payment_dto = PaymentRequestDTO::fromPaymentData($this->payment);

        if (!$this->payment_dto) {
            Log::error('Failed to create payment dto for payment', [
                'payment' => $this->payment,
            ]);

            throw new Exception('Failed to create payment DTO.');
        }

        return $this;
    }

    public function determineParentOrderId(): self
    {
        $this->parent_order_id = method_exists($this->repository, 'getParentOrderId')
            ? $this->repository->getParentOrderId()
            : null;

        return $this;
    }

    public function requestPayment(): self
    {
        $this->response_dto = $this->repository->requestPayment($this->payment_dto, $this->parent_order_id);

        if (!$this->response_dto) {
            throw new Exception('Payment request failed.');
        }

        return $this;
    }

    public function updatePaymentWithGatewayId(): self
    {
        $updated = $this->repository->writeGatewayID($this->payment_id, [
            'gateway_transaction_id' => $this->response_dto->getOrderID(),
        ]);

        if (!$updated) {
            Log::error('Failed to update payment record.', [
                'payment_id' => $this->payment_id,
                'gateway_transaction_id' => $this->response_dto->getOrderID(),
            ]);

            throw new Exception('Failed to update payment record.');
        }

        return $this;
    }

    public function saveCardIfNeeded(): self
    {
        if (!$this->parent_order_id) {
            $this->repository->saveCard($this->payment_id, $this->response_dto->getOrderID());
        }

        return $this;
    }

    public function getRedirectUrl(): string
    {
        return $this->response_dto->getRedirectUrl();
    }

    public function process(DBPaymentData $cart_dto)
    {
        return $this->withCartData($cart_dto)
            ->createPaymentRecord()
            ->findPayment()
            ->createPaymentDTO()
            ->determineParentOrderId()
            ->requestPayment()
            ->updatePaymentWithGatewayId()
            ->saveCardIfNeeded();
    }
}
