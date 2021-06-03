<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\User;
use Illuminate\Http\Request;

class AccountsController extends Controller
{
    public function store(Request $request, User $customer)
    {
        $request->validate([
            'amount' => 'required|numeric'
        ]);

        $account = $customer->accounts()->create([
            'balance' => $request->amount
        ]);

        $account->history()->create([
            'description' => 'Deposit',
            'amount' => $request->amount,
            'balance' => $account->balance
        ]);

        return $account;
    }

    public function balance(BankAccount $account)
    {
        return $account->balance;
    }

    public function transferAmounts(Request $request)
    {
        $request->validate([
            'from' => 'required|numeric|exists:bank_accounts,id',
            'to' => 'required|numeric|exists:bank_accounts,id',
            'amount' => 'required|numeric'
        ]);

        $fromAccount = BankAccount::find($request->from);

        if($fromAccount->balance < $request->amount) {
            abort(405, 'Insufficient funds.');
        }

        $toAccount = BankAccount::find($request->to);

        $fromAccount->decrement('balance', $request->amount);
        $fromAccount->history()->create([
            'description' => 'transferred amount to account: '. $toAccount->id,
            'amount' => $request->amount,
            'balance' => $fromAccount->balance
        ]);

        $toAccount->increment('balance', $request->amount);
        $fromAccount->history()->create([
            'description' => 'received amount from account: '. $fromAccount->id,
            'amount' => $request->amount,
            'balance' => $toAccount->balance
        ]);

        return response('Amount transferred successfully');
    }

    public function history(BankAccount $account)
    {
        return $account->history()->paginate(10);
    }
}