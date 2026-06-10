<?php

namespace App\Console\Commands;

use App\Models\Transaction;
use App\Services\Payment\PaymentService;
use Illuminate\Console\Command;

class RetryPendingTransactions extends Command
{
    protected $signature = 'payments:retry-pending';
    protected $description = 'Auto-verify pending transactions older than 5 minutes with Paystack';

    public function handle(PaymentService $paymentService): void
    {
        $cutoff = now()->subMinutes(5);

        $pending = Transaction::where('status', 'pending')
            ->where('created_at', '<', $cutoff)
            ->get();

        if ($pending->isEmpty()) {
            $this->info('No stale pending transactions found.');
            return;
        }

        $this->info("Found {$pending->count()} pending transaction(s). Verifying...");

        foreach ($pending as $transaction) {
            try {
                $result = $paymentService->verifyAndProcessPayment($transaction->transaction_id);

                $status = $result['transaction']['status'] ?? 'unknown';
                $this->info("  ✓ {$transaction->transaction_id} — {$status}");
            } catch (\Exception $e) {
                $paymentService->markTransactionAsFailed($transaction->transaction_id);
                $this->warn("  ✗ {$transaction->transaction_id} — marked failed: {$e->getMessage()}");
            }
        }

        $this->info('Done.');
    }
}
