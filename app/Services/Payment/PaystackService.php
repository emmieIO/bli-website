<?php

namespace App\Services\Payment;

use Illuminate\Support\Facades\Log;

/**
 * Service for interacting with Paystack API
 * Responsible for: Payment gateway communication
 */
class PaystackService
{
    private string $secretKey;
    private string $publicKey;
    private string $baseUrl;

    public function __construct()
    {
        $this->secretKey = config('services.paystack.secret_key');
        $this->publicKey = config('services.paystack.public_key');
        $this->baseUrl = config('services.paystack.base_url');
    }

    /**
     * Initialize a transaction with Paystack
     */
    public function initializePayment(array $data): array
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "{$this->baseUrl}/transaction/initialize",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer {$this->secretKey}",
                "Content-Type: application/json"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            Log::error("Paystack API Error", ['error' => $err]);
            throw new \Exception("cURL Error: {$err}");
        }

        $result = json_decode($response, true);

        if (!$result || !isset($result['status'])) {
            Log::error("Invalid Paystack response", ['response' => $response]);
            throw new \Exception("Invalid response from Paystack");
        }

        return $result;
    }

    /**
     * Verify transaction with Paystack API
     */
    public function verifyTransaction(string $reference): array
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "{$this->baseUrl}/transaction/verify/{$reference}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer {$this->secretKey}",
                "Content-Type: application/json"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            Log::error("Paystack API Error", ['error' => $err]);
            throw new \Exception("cURL Error: {$err}");
        }

        $result = json_decode($response, true);

        if (!$result || !isset($result['status'])) {
            Log::error("Invalid Paystack response", ['response' => $response]);
            throw new \Exception("Invalid response from Paystack");
        }

        return $result;
    }

    /**
     * Verify webhook signature
     */
    public function verifyWebhookSignature(string $payload, string $signature): bool
    {
        return hash_hmac('sha512', $payload, $this->secretKey) === $signature;
    }

    /**
     * Get public key for frontend
     */
    public function getPublicKey(): string
    {
        return $this->publicKey;
    }
}
