<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Helpers\Luhn;

class AccountController extends Controller
{
    public function index() {
        //dd(auth()->user());

        return response()->json(auth()->user()->accounts);
    }

    public function store(Request $request) {
        $request->validate([
            'account_name' => 'required|string|unique:accounts',
            'account_type' => 'required|in:Personal,Business',
            'currency' => 'required|in:USD,EUR,GBP',
        ]);

        $account = Account::create([
            'id' => (string) Str::uuid(),
            'user_id' => auth()->id(),
            'account_name' => $request->account_name,
            'account_number' => Luhn::generateAccountNumber(),
            'account_type' => $request->account_type,
            'currency' => $request->currency,
            'balance' => 0,
        ]);

        return response()->json($account, 201);
    }

    public function show($id) {
        $account = Account::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        return response()->json($account);
    }

    public function update(Request $request, $id) {
        $account = Account::where('id', $id)->where('user_id', auth()->id())->firstOrFail();

        $request->validate([
            'account_name' => 'sometimes|string|unique:accounts,account_name,' . $account->id,
        ]);

        $account->update($request->only(['account_name']));
        return response()->json(['message' => 'Account updated']);
    }

    public function destroy($id) {
        $account = Account::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        $account->delete();
        return response()->json(['message' => 'Account deleted']);
    }
}
