<?php

namespace App\Services\Payment;

use Illuminate\Support\Facades\Log;

/**
 * Service for interacting with Flutterwave API
 * Responsible for: Payment gateway communication
 */
class FlutterwaveService
{
    private string $secretKey;
    private string $publicKey;
    private string $encryptionKey;

    public function __construct()
    {
        $this->secretKey = config('services.flutterwave.secret_key');
        $this->publicKey = config('services.flutterwave.public_key');
        $this->encryptionKey = config('services.flutterwave.encryption_key');
    }

    /**
     * Prepare payment data for Flutterwave
     */
    public function preparePaymentData(array $data): array
    {
        return [
            'tx_ref' => $data['tx_ref'],
            'amount' => $data['amount'],
            'currency' => $data['currency'],
            'redirect_url' => $data['redirect_url'],
            'customer' => $data['customer'],
            'customizations' => $data['customizations'],
            'meta' => $data['meta'],
        ];
    }

    /**
     * Verify transaction with Flutterwave API
     */
    public function verifyTransaction(string $transactionId): array
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.flutterwave.com/v3/transactions/{$transactionId}/verify",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "Authorization: Bearer {$this->secretKey}"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            Log::error("Flutterwave API Error", ['error' => $err]);
            throw new \Exception("cURL Error: {$err}");
        }

        $result = json_decode($response, true);

        if (!$result || !isset($result['status'])) {
            Log::error("Invalid Flutterwave response", ['response' => $response]);
            throw new \Exception("Invalid response from Flutterwave");
        }

        return $result;
    }

    /**
     * Verify webhook signature
     */
    public function verifyWebhookSignature(?string $signature): bool
    {
        $secretHash = config('services.flutterwave.secret_hash');

        if (!$signature || !$secretHash) {
            return false;
        }

        return $signature === $secretHash;
    }

    /**
     * Get public key for frontend
     */
    public function getPublicKey(): string
    {
        return $this->publicKey;
    }
}
