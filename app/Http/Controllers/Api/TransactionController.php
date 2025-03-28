<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index(Request $request, $accountId) {
        $request->validate([
            'from' => 'nullable|date',
            'to' => 'nullable|date|after_or_equal:from',
        ]);
    
        $transactions = Transaction::where('account_id', $accountId);
    
        if ($request->has('from')) {
            $transactions->whereDate('created_at', '>=', $request->from);
        }
        if ($request->has('to')) {
            $transactions->whereDate('created_at', '<=', $request->to);
        }
    
        return response()->json($transactions->get(), 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'account_id' => 'required|uuid|exists:accounts,id',
            'amount' => 'required|numeric|min:0.01',
            'type' => 'required|in:credit,debit',
            'description' => 'nullable|string|max:255',
        ]);

        $account = Auth::user()->accounts()->where('id', $request->account_id)->first();

        if (!$account) {
            return response()->json(['error' => 'Account not found or unauthorized'], 404);
        }

        if ($request->type === 'debit' && $account->balance < $request->amount) {
            return response()->json(['error' => 'Insufficient balance'], 400);
        }

        // Create the transaction
        $transaction = Transaction::create([
            'account_id' => $request->account_id,
            'amount' => $request->amount,
            'type' => $request->type,
            'description' => $request->description ?? null,
        ]);

        // Update the account balance
        if ($request->type === 'credit') {
            $account->increment('balance', $request->amount);
        } else {
            $account->decrement('balance', $request->amount);
        }

        return response()->json([
            'message' => 'Transaction logged successfully',
            'transaction' => $transaction,
            'new_balance' => $account->balance
        ], 201);
    }
}
