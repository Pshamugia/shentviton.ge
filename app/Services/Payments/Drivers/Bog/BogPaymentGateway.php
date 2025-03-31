<?php

namespace App\Services\Payments\Drivers\Bog;

use Exception;
use Ramsey\Uuid\Rfc4122\UuidV4;
use App\Services\Payments\Gateway;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Http;
use App\Services\Payments\PaymentGatewayInterface;
use App\Services\Payments\Drivers\Bog\DTO\PaymentResponseDTO;

/**
 * Class BogPaymentGateway
 *
 * This is a specific implementation of the PaymentGatewayInterface.
 * It represents a payment gateway for BOG (Bank of Ghana).
 * The class contains methods to handle payment processing through BOG.
 */
class BogPaymentGateway extends Gateway implements PaymentGatewayInterface
{

    protected $name = "BOG";
    private $id;
    private $secret;
    private $bearer_token;

    public function __construct()
    {
        $this->id = config("services.payments.bog.id");
        $this->secret = config("services.payments.bog.secret");

        if (empty($this->id || empty($this->secret))) {
            throw new Exception("Bog Payment Gateway credentials not set.");
        }
    }

    public function charge(float $amount, array $options = [])
    {
        //
    }

    public function authenticate()
    {
        try {
            $res = Http::withBasicAuth($this->id, $this->secret)
                ->asForm()
                ->post("https://oauth2.bog.ge/auth/realms/bog/protocol/openid-connect/token", [
                    "grant_type" => "client_credentials"
                ]);

            $data = $res->json();

            if ($res->successful()) {
                $this->bearer_token = $data["access_token"];
            } else {
                Log::error('BOG Authentication Failed', [
                    'status' => $res->status(),
                    'body' => $res->body(),
                ]);

                throw new Exception('Authentication failed with Bank of Georgia.');
            }
        } catch (Exception $e) {
            Log::error('Authentication Exception: ' . $e->getMessage());
            throw $e;
        }

        return $this;
    }

    /**
     * $parent_order_id represents the order, where the user's card was saved for the first time
     * $options is payment related data
     */
    public function requestPayment(object $options, ?string $parent_order_id = null)
    {
        $this->ensureServiceIsAuthenticated();
        $url = "https://api.bog.ge/payments/v1/ecommerce/orders";
        $callback_url = route('payment.callback');

        /**
         * if parent order id is at hand, we change the url.
         * Header, Body, Request is the same as in the case of base version url.
         * I AM DELIBERATELY NOT USING THIS FEATURE, BUT LEAVING IT HERE IF FOUND ACCEPTABLE.
         *
         * START CODE:
         * if (!empty($parent_order_id)) {
         *    $url .= "/" . $parent_order_id;
         * }
         * END CODE
         */


        $payload = [
            "external_order_id" => $options->payment_id,
            "purchase_units" => (object) [
                "currency" => "GEL",
                "total_amount" => $options->total_amount,
                "basket" => $options->basket,
            ],
            "redirect_urls" => [
                "fail" => URL::signedRoute('payment.status.closed', ['status_string' => 'fail', 'payment_id' => $options->payment_id]),
                "success" => URL::signedRoute('payment.status.closed', ['status_string' => 'success', 'payment_id' => $options->payment_id]),
            ]
        ];



        if ($this->shouldForceHttpsInUrl()) {
            $payload["callback_url"] = str_replace("http://", "https://", $callback_url);
        } else {
            $payload["callback_url"] = $callback_url;
        }

        try {
            $res = Http::withHeaders([
                "Accept-Language" => "ka",
                "Authorization" => "Bearer " . $this->bearer_token,
                "Content-Type" => "application/json",
                "Idempotency-Key" => UuidV4::uuid4()->toString(),
                "Theme" => "Light",
            ])->post($url, $payload);


            $data = $res->json();

            if ($res->successful()) {
                $res_dto = PaymentResponseDTO::fromArray($data);

                if ($res_dto) {
                    return $res_dto;
                } else {
                    Log::error('BOG Payment Response DTO Creation Failed', [
                        'data' => $data,
                    ]);

                    throw new Exception('Failed to create PaymentResponseDTO from BOG response.');
                }
            } else {
                dd($data, $res);
                Log::error('BOG Payment Order Request Failed', [
                    'status' => $res->status(),
                    'body' => $res->body(),
                ]);

                throw new Exception('Requesting Order failed with Bank of Georgia.');
            }
        } catch (Exception $e) {
            Log::error('Payment Request Exception: ' . $e->getMessage());
            throw $e;
        }
    }

    public function retrievePaymentInfo($gateway_order_id)
    {
        $this->ensureServiceIsAuthenticated();

        try {
            $res =  Http::withHeaders([
                "Authorization" => "Bearer " . $this->bearer_token,
            ])->get("https://api.bog.ge/payments/v1/receipt/{$gateway_order_id}");

            $data = $res->json();

            if ($res->successful()) {
                dd($data);
            } else {
                Log::error('BOG Payment Info Retrieval Failed', [
                    'status' => $res->status(),
                    'body' => $res->body(),
                ]);

                throw new Exception('Retrieving Payment Info failed with Bank of Georgia.');
            }
        } catch (Exception $e) {
            Log::error('Payment Info Retrieval Exception: ' . $e->getMessage());
            throw $e;
        }
    }

    // ამისთვის, ბიზნესმა შეკვეთის მოთხოვნის შემდეგ, გადახდების გვერდზე მომხმარებლის გადამისამართებამდე,
    // უნდა გამოიძახოს შესაბამისი მეთოდი და გადასცეს მას შეკვეთის იდენტიფიკატორი.
    // თუ მოცემულ შეკვეთაზე წარმატებით შესრულდება საბარათე გადახდა,
    // შემდგომი შეკვეთების მოთხოვნისას ამავე იდენტიფიკატორის გამოყენებას შეძლებთ.
    public function saveCardForFuturePayments(string $gateway_order_id)
    {
        $this->ensureServiceIsAuthenticated();

        try {
            $res = Http::withHeaders([
                "Authorization" => "Bearer " . $this->bearer_token,
            ])->put("https://api.bog.ge/payments/v1/orders/{$gateway_order_id}/cards");

            $data = $res->json();

            if (!$res->successful()) {
                Log::error('BOG Card Save Failed', [
                    'status' => $res->status(),
                    'body' => $res->body(),
                ]);
                return false;
            }

            return $data ?? true;
        } catch (Exception $e) {
            Log::error('Card Saving Exception: ' . $e->getMessage());
            throw $e;
        }
    }


    public function getName(): string
    {
        return $this->name;
    }

    private function ensureServiceIsAuthenticated(): void
    {
        if (empty($this->bearer_token)) {
            $this->authenticate();
        }
    }

    private function shouldForceHttpsInUrl(): bool
    {
        return !$this->isProductionENV() || !$this->isRequestSecureContext();
    }

    private function isProductionENV(): bool
    {
        return config('app.env') !== 'local';
    }

    private function isRequestSecureContext(): bool
    {
        return request()->secure();
    }
}
