<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Cart;
use App\Models\Payment;
use App\Mail\NewPaymentMail;
use Illuminate\Http\Request;
use App\Mail\OrderStatusMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\PaymentRequest;
use App\Repositories\PaymentRepository;
use App\Services\Payments\PaymentProcessor;
use Symfony\Component\HttpKernel\Exception\HttpException;

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

        return redirect()->to(route('payment.status.public', ['id' => $payment_id]));
    }

    public function callback(Request $request)
    {
        $payload = $request->getContent();
        $signature = $request->header('Callback-Signature');

        $this->ensureSignatureIsValid($payload, $signature, config('services.bog.id'));

        $data = json_decode($payload, true);

        $payment_id = $data['external_order_id'] ?? null;
        $order_status = $data['order_status']['key'] ?? null;

        if (!$payment_id || !$order_status) {
            Log::error('Invalid callback payload', ['data' => $data]);
            return response('Invalid payload', 400);
        }

        $status = match ($order_status) {
            'completed' => 'success',
            'processing' => 'pending',
            default => $order_status,
        };

        $payment_status_updated = $this->repository->updatePaymentStatus($payment_id, $status);
        $cart_updated = $this->repository->updateCarts($payment_id, $status);

        $payment = Payment::find($payment_id);

        if (!$payment) {
            Log::error('Payment not found', ['payment_id' => $payment_id]);
            return response('Payment not found', 404);
        }

        if ($payment_status_updated && $cart_updated) {
            if ($status === 'success') {
                Mail::to(config('mail.to.admin.address'))
                    ->send(new NewPaymentMail($payment));
            }

            Mail::to($payment->u_email)
                ->send(new OrderStatusMail($payment));
        } else {
            Log::error('Payment status update failed.', [
                'status' => $status,
                'payment_id' => $payment_id,
            ]);
        }

        return response('OK', 200);
    }


    private function ensureSignatureIsValid(string $data, ?string $signature, ?string $publicKey)
    {
        if (! $this->verifySignature($data, $signature, $publicKey)) {
            Log::error('Invalid RSA Signature', [
                'signature' => $signature,
                'payload' => $data,
            ]);
            throw new HttpException(401, 'Invalid Signature');
        }
    }


    private function verifySignature(string $data, ?string $signature, ?string $publicKey): bool
    {
        $decodedSignature = base64_decode($signature);

        $publicKey = openssl_pkey_get_public($publicKey);
        $verified = openssl_verify($data, $decodedSignature, $publicKey, 'RSA-SHA256');

        return $verified === 1;
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
