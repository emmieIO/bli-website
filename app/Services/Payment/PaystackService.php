<?php

namespace App\Services\Payment;

use App\Contracts\Services\PaymentGatewayInterface;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Service for interacting with Paystack API
 * Responsible for: Payment gateway communication
 */
class PaystackService implements PaymentGatewayInterface
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
        return $this->sendRequest('post', '/transaction/initialize', $data);
    }

    /**
     * Verify transaction with Paystack API
     */
    public function verifyTransaction(string $reference): array
    {
        return $this->sendRequest('get', "/transaction/verify/{$reference}");
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

    private function sendRequest(string $method, string $uri, array $payload = []): array
    {
        $method = strtoupper($method);

        $options = match ($method) {
            'GET' => $payload === [] ? [] : ['query' => $payload],
            default => $payload === [] ? [] : ['json' => $payload],
        };

        try {
            $response = Http::baseUrl($this->baseUrl)
                ->withToken($this->secretKey)
                ->acceptJson()
                ->asJson()
                ->timeout(60)
                ->send($method, $uri, $options)
                ->throw();
        } catch (RequestException $exception) {
            Log::error('Paystack API request failed', [
                'method' => $method,
                'uri' => $uri,
                'status' => $exception->response?->status(),
                'response' => $exception->response?->json(),
                'error' => $exception->getMessage(),
            ]);

            throw $exception;
        }

        $result = $response->json();

        if (! is_array($result) || ! array_key_exists('status', $result) || ! is_bool($result['status'])) {
            Log::error('Invalid Paystack response', [
                'method' => $method,
                'uri' => $uri,
                'response' => $response->body(),
            ]);

            throw new \RuntimeException('Invalid response from Paystack');
        }

        return $result;
    }
}
