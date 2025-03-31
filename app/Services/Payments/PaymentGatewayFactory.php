<?php

namespace App\Services\Payments;

use App\Services\Payments\Drivers\Bog\BogPaymentGateway;
use Illuminate\Support\Facades\Config;
use InvalidArgumentException;

class PaymentGatewayFactory
{
    /**
     * This factory method will decide which payment driver to use
     * based on the value of config('services.payments.driver')
     * Do note that return type is Interface and if driver will be addded it should implement the interface
     */
    public static function make(): PaymentGatewayInterface
    {
        $driver = Config::get('services.payments.driver');

        return match ($driver) {
            'bog' => new BogPaymentGateway(),
            default => throw new InvalidArgumentException("Unsupported payment driver: $driver"),
        };
    }
}
