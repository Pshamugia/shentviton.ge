<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Cart;
use App\Services\Payments\PaymentProcessor;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\PaymentRequest;
use App\Repositories\PaymentRepository;

class PaymentController extends Controller
{
    /**
     * Using the repository pattern to handle payment-related operations.
     * This allows for better separation of concerns and easier testing.
     * Business logic will be inside the repository.
     * Repository already knows correct driver to use.
     */
    protected PaymentRepository $repository;

    public function __construct(PaymentRepository $repository)
    {
        $this->repository = $repository;
    }

    public function info()
    {
        return response()->json($this->repository->info());
    }

    public function pay(PaymentRequest $request)
    {

        try {
            $validated = $request->validated();

            $cartDTO = Cart::createDBDTO($validated);

            if (!$cartDTO) {
                return redirect()->back();
            }

            $processor = new PaymentProcessor($this->repository);
            $processor->process($cartDTO);

            return redirect()->to($processor->getRedirectUrl());
        } catch (Exception $e) {
            Log::error('Payment processing failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->with('error', $e->getMessage());
        }
    }

    public function status()
    {
        if (!request()->hasValidSignature()) {
            abort(403, 'Invalid or tampered payment URL.');
        }


        $status = request('status_string') ?? null;
        $payment_id = request('payment_id') ?? null;

        if (!$status || !$payment_id) {
            Log::error('Payment status request failed.', [
                'status' => $status,
                'payment_id' => $payment_id,
            ]);
            abort(400, 'Invalid request.');
        }


        $payment_status_updated = $this->repository->updatePaymentStatus($payment_id, $status);
        $updated_cart = $this->repository->updateCartPaymentID($payment_id);

        if ($payment_status_updated) {
            return redirect()->to(route('payment.status.public', ['id' => $payment_id]));
        } else {
            Log::error('Payment status update failed.', [
                'status' => $status,
                'payment_id' => $payment_id,
            ]);
            abort(500, 'Payment status update failed.');
        }
    }

    public function callback()
    {
        dd("callback");
    }

    public function publicStatus($id)
    {
        if (!$id) {
            abort(404);
        }

        $payment = $this->repository->find($id);
        $payment->carts = $payment->carts();

        return view('payment.status', [
            'payment' => $payment,
        ]);
    }
}
