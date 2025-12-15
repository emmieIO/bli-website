<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\InstructorPayout;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TransactionAuditController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('q');

        $transactions = Transaction::with(['user', 'course'])
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

        $payouts = InstructorPayout::with(['instructor'])
            ->when($search, function ($query, $search) {
                $query->where('payout_reference', 'like', "%{$search}%")
                      ->orWhereHas('instructor', function ($q) use ($search) {
                          $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                      });
            })
            ->latest()
            ->paginate(15, ['*'], 'payouts_page')
            ->withQueryString();

        return Inertia::render('Admin/TransactionAudit/Index', [
            'transactions' => $transactions,
            'payouts' => $payouts,
            'filters' => $request->only(['q']),
        ]);
    }
}
