<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\InstructorPayout;
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
            ->with(['course:id,title,slug,thumbnail_path'])
            ->orderBy('created_at', 'desc')
            ->paginate(15, ['*'], 'transactions_page');

        // Format transactions for display
        $formattedTransactions = $transactions->through(function ($transaction) {
            $metadata = $transaction->metadata ?? [];
            $isCartPurchase = isset($metadata['type']) && $metadata['type'] === 'cart';

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
                'type' => $isCartPurchase ? 'cart' : 'course',
                'course' => $transaction->course ? [
                    'id' => $transaction->course->id,
                    'title' => $transaction->course->title,
                    'slug' => $transaction->course->slug,
                    'thumbnail_path' => $transaction->course->thumbnail_path,
                ] : null,
                'items' => $isCartPurchase ? ($metadata['items'] ?? []) : null,
                'item_count' => $isCartPurchase ? count($metadata['items'] ?? []) : 1,
            ];
        });

        // Fetch Payouts (if user is instructor)
        $payouts = InstructorPayout::where('instructor_id', $user->id)
            ->orderBy('requested_at', 'desc')
            ->paginate(15, ['*'], 'payouts_page');

        return Inertia::render('Dashboard/Transactions/Index', [
            'transactions' => $formattedTransactions,
            'payouts' => $payouts,
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

        $transaction->load(['user', 'course']); // Eager load relations for receipt details

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
                'metadata' => $transaction->metadata, // Pass raw metadata for item details
                'user' => [
                    'name' => $transaction->user->name,
                    'email' => $transaction->user->email,
                ],
                'course' => $transaction->course ? [
                    'title' => $transaction->course->title,
                ] : null,
            ],
            // Pass company details from config if available (e.g., config('app.company_name'))
            'companyDetails' => [
                'name' => config('app.name'),
                'address' => '123 Main Street, City, Country', // Placeholder, update from config
                'email' => 'support@example.com', // Placeholder, update from config
                'phone' => '+1 (123) 456-7890', // Placeholder, update from config
            ]
        ]);
    }
}
