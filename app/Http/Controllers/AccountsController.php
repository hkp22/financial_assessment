<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewBankAccountRequest;
use App\Http\Requests\TransferAmountRequest;
use App\Models\BankAccount;
use App\Models\User;
use App\Services\BankAccountService;

class AccountsController extends Controller
{
    public function store(NewBankAccountRequest $request, User $customer)
    {
        $account = BankAccountService::create($customer, $request);

        return response()->json($account);
    }

    public function balance(BankAccount $account)
    {
        return response()->json([
            'account' => $account->id,
            'balance' => $account->balance
        ]);
    }

    public function transferAmounts(TransferAmountRequest $request)
    {
        BankAccountService::transferAmount($request);

        return response()->json([
            'success' => true,
            'message' => 'Amount transferred successfully'
        ]);
    }

    public function history(BankAccount $account)
    {
        return response()->json(
            $account->history()->paginate(10)
        );
    }
}