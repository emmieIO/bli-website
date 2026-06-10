<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Services\Payment\PaymentService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TransactionAuditController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('q');

        $transactions = Transaction::with(['user', 'payable'])
            ->when($search, function ($query, $search) {
                $query->where('transaction_id', 'like', "%{$search}%")
                      ->orWhere('payment_ref', 'like', "%{$search}%")
                      ->orWhereHas('user', function ($q) use ($search) {
                          $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                      });
            })
            ->latest()
            ->paginate(15, ['*'], 'transactions_page')
            ->withQueryString();

        return Inertia::render('Admin/TransactionAudit/Index', [
            'transactions' => $transactions,
            'filters' => $request->only(['q']),
        ]);
    }

    public function resolve(Transaction $transaction, PaymentService $paymentService)
    {
        if ($transaction->status === 'successful') {
            return back()->with([
                'type' => 'info',
                'message' => 'Transaction is already marked as successful.',
            ]);
        }

        try {
            $result = $paymentService->verifyAndProcessPayment($transaction->transaction_id);
            $status = $result['transaction']['status'] ?? 'successful';

            return back()->with([
                'type' => 'success',
                'message' => "Transaction verified — status: {$status}.",
            ]);
        } catch (\Exception $e) {
            return back()->with([
                'type' => 'error',
                'message' => 'Verification failed: ' . $e->getMessage(),
            ]);
        }
    }
}
