<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Course;
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
            ->with(['course:id,title,slug,thumbnail_path', 'payable'])
            ->orderBy('created_at', 'desc')
            ->paginate(15, ['*'], 'transactions_page');

        // Format transactions for display
        $formattedTransactions = $transactions->through(function ($transaction) {
            $metadata = $transaction->metadata ?? [];
            $subjectType = $metadata['subject_type']
                ?? ($transaction->payable_type === Event::class ? 'event' : (($metadata['type'] ?? null) === 'cart' ? 'cart' : 'course'));
            $checkoutContext = $metadata['checkout_context'] ?? (($metadata['type'] ?? null) === 'cart' ? 'cart' : 'direct');
            $isCartPurchase = $checkoutContext === 'cart';
            $payable = $transaction->payable;
            $event = $payable instanceof Event ? $payable : null;
            $course = $transaction->course ?? ($payable instanceof Course ? $payable : null);

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
                'checkout_context' => $checkoutContext,
                'course' => $course ? [
                    'id' => $course->id,
                    'title' => $course->title,
                    'slug' => $course->slug,
                    'thumbnail_path' => $course->thumbnail_path,
                ] : null,
                'event' => $event ? [
                    'id' => $event->id,
                    'title' => $event->title,
                    'slug' => $event->slug,
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

        $transaction->load(['user', 'course', 'payable']); // Eager load relations for receipt details

        $payable = $transaction->payable;
        $event = $payable instanceof Event ? $payable : null;
        $course = $transaction->course ?? ($payable instanceof Course ? $payable : null);

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
                'course' => $course ? [
                    'title' => $course->title,
                ] : null,
                'event' => $event ? [
                    'title' => $event->title,
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
