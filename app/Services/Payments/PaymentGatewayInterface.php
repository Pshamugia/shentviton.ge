<?php

namespace App\Services\Payments;

/**
 * Interface PaymentGatewayInterface
 *
 * This interface defines the contract for payment gateways.
 * It outlines the methods that any class implementing this interface must define.
 */
interface PaymentGatewayInterface
{
    public function retrievePaymentInfo($payment_identifier);
    public function requestPayment(object $payment_dto);
    public function getName(): string;
}
