<?php

namespace App\Services;

use App\Models\BankAccount;
use App\Models\User;
use Illuminate\Http\Request;

class BankAccountService
{
    public static function create(User $customer, Request $request)
    {
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

    public static function transferAmount(Request $request)
    {
        $fromAccount = BankAccount::find($request->from);

        if ($fromAccount->balance < $request->amount) {
            abort(405, 'Insufficient funds.');
        }

        $toAccount = BankAccount::find($request->to);

        $fromAccount->withdrawAmount(
            $request->amount,
            "transferred amount to account: {$toAccount->id}"
        );

        $toAccount->addAmount(
            $request->amount,
            "received amount from account: {$fromAccount->id}"
        );
    }
}