<?php

namespace App\Contracts\Services;

interface PaymentGatewayInterface
{
    public function initializePayment(array $data): array;

    public function verifyTransaction(string $reference): array;

    public function verifyWebhookSignature(string $payload, string $signature): bool;

    public function getPublicKey(): string;
}
