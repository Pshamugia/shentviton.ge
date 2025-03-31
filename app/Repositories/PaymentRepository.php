<?php

namespace App\Repositories;

use App\Models\Cart;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Database\Eloquent\Collection;
use App\Services\Payments\PaymentGatewayInterface;

/**
 * Class PaymentRepository
 *
 * This class implements the PaymentRepositoryInterface and provides
 * concrete implementations for the methods defined in the interface.
 * It uses the Payment model to interact with the database.
 * Business logic related to payments is handled here.
 */
class PaymentRepository
{
    protected PaymentGatewayInterface $gateway;

    public function __construct(PaymentGatewayInterface $gateway)
    {
        $this->gateway = $gateway;
    }

    public function info()
    {
        $this->gateway->authenticate()->requestOrder();
        return $this->gateway;
    }

    public function all(): Collection
    {
        return Payment::all();
    }

    public function find(int $id): ?Payment
    {
        return Payment::find($id);
    }

    public function create(array $data): Payment
    {
        $this->gateway->charge($data['amount'], $data);

        return Payment::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $payment = Payment::find($id);
        return $payment ? $payment->update($data) : false;
    }

    public function delete(int $id): bool
    {
        $payment = Payment::find($id);
        return $payment ? $payment->delete() : false;
    }

    public function requestPayment(object $payment_dto, string $parent_order_id): object|bool
    {
        $res = $this->gateway->requestPayment($payment_dto, $parent_order_id);

        if ($res) {
            return $res;
        } else {
            // todo handle error
            return false;
        }
    }

    public function createDBRecord(object $dto): int|bool
    {
        $dto->status = 'pending';
        $dto->currency = 'GEL';
        $dto->gateway = $this->gateway->getName();
        $dto->gateway_transaction_id = null;
        $dto->created_at = now();
        $dto->updated_at = now();

        $set = (array) $dto;

        $id = Payment::insertGetId($set);
        return $id ? $id : false;
    }

    public function updatePaymentStatus(int $id, string $status): bool
    {
        $payment = Payment::find($id);
        $payment->status = $status;
        $payment->updated_at = now();
        $payment->save();

        return $payment->wasChanged();
    }

    public function writeGatewayID(int $gateway_id, array $data): bool
    {
        $payment = Payment::find($gateway_id);
        $payment->gateway_transaction_id = $data['gateway_transaction_id'];
        $payment->updated_at = now();
        $payment->save();

        return $payment->wasChanged();
    }

    public function getParentOrderId(): string|null
    {
        $visitor_hash = session('v_hash');
        $user_id = auth()->id();


        $parent_order_id = Payment::where('visitor_hash', $visitor_hash)
            ->orWhere('user_id', $user_id)
            ->where('is_parent_order', true)
            ->value('gateway_transaction_id');

        return $parent_order_id ?? null;
    }

    public function saveCard($payment_id, $gateway_transaction_id): bool
    {
        $card_saved = $this->gateway->saveCardForFuturePayments($gateway_transaction_id);
        if (!$card_saved) {
            return false;
        }
        $payment = Payment::find($payment_id);
        $payment->is_parent_order = true;
        $payment->save();
        return $payment->wasChanged();
    }

    public function updateCartPaymentID($payment_id)
    {
        $payment = Payment::find($payment_id);

        $cart_ids = $payment->cart_ids;

        foreach ($cart_ids as $cart_id) {
            $cart = Cart::find($cart_id);
            if ($cart) {
                $cart->payment_id = $payment_id;
                $cart->save();
            }
        }
    }
}
