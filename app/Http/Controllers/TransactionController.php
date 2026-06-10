<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TransactionController extends Controller
{
    /**
     * Display transaction history for the authenticated user
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        $transactions = Transaction::where('user_id', $user->id)
            ->with(['payable'])
            ->orderBy('created_at', 'desc')
            ->paginate(15, ['*'], 'transactions_page');

        // Format transactions for display
        $formattedTransactions = $transactions->through(function ($transaction) {
            $metadata = $transaction->metadata ?? [];
            $subjectType = $metadata['subject_type']
                ?? ($transaction->payable_type === Event::class ? 'event' : 'payment');
            $payable = $transaction->payable;
            $event = $payable instanceof Event ? $payable : null;

            return [
                'id' => $transaction->id,
                'transaction_id' => $transaction->transaction_id,
                'payment_ref' => $transaction->payment_ref,
                'amount' => $transaction->amount,
                'currency' => $transaction->currency,
                'status' => $transaction->status,
                'payment_type' => $transaction->payment_type,
                'created_at' => $transaction->created_at->format('M d, Y h:i A'),
                'paid_at' => $transaction->paid_at?->format('M d, Y h:i A'),
                'type' => $subjectType,
                'event' => $event ? [
                    'id' => $event->id,
                    'title' => $event->title,
                    'slug' => $event->slug,
                ] : null,
            ];
        });

        return Inertia::render('Dashboard/Transactions/Index', [
            'transactions' => $formattedTransactions,
        ]);
    }

    /**
     * Display a full receipt/invoice for a specific transaction.
     */
    public function showReceipt(Transaction $transaction)
    {
        // Authorize: Only the user who owns the transaction OR an admin can view its receipt
        if (auth()->id() !== $transaction->user_id && !auth()->user()->can('view-transaction-audit')) {
            abort(403, 'Unauthorized to view this receipt.');
        }

        $transaction->load(['user', 'payable']);

        $payable = $transaction->payable;
        $event = $payable instanceof Event ? $payable : null;

        return Inertia::render('Dashboard/Transactions/Receipt', [
            'transaction' => [
                'id' => $transaction->id,
                'transaction_id' => $transaction->transaction_id,
                'payment_ref' => $transaction->payment_ref,
                'amount' => $transaction->amount,
                'currency' => $transaction->currency,
                'status' => $transaction->status,
                'payment_type' => $transaction->payment_type,
                'created_at' => $transaction->created_at->format('M d, Y h:i A'),
                'paid_at' => $transaction->paid_at?->format('M d, Y h:i A'),
                'metadata' => $transaction->metadata,
                'user' => [
                    'name' => $transaction->user->name,
                    'email' => $transaction->user->email,
                ],
                'event' => $event ? [
                    'title' => $event->title,
                ] : null,
            ],
            'companyDetails' => [
                'name' => config('app.name'),
                'address' => \App\Models\Setting::get('contact_address', env('CONTACT_ADDRESS', 'Lagos, Nigeria')),
                'email' => \App\Models\Setting::get('contact_email', env('CONTACT_EMAIL', 'info@beaconleadership.org')),
                'phone' => \App\Models\Setting::get('contact_phone', env('CONTACT_PHONE', '+234-706-442-5639')),
            ]
        ]);
    }
}
