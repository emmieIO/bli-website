<?php

namespace App\Http\Controllers;

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
            ->with(['course:id,title,slug,thumbnail_path'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

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

        return Inertia::render('Dashboard/Transactions/Index', [
            'transactions' => $formattedTransactions,
        ]);
    }
}
