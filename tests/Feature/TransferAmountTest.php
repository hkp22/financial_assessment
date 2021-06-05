<?php

namespace Tests\Feature;

use App\Models\BankAccount;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransferAmountTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function it_may_transfer_amount_to_other_account()
    {
        $fromAccount = BankAccount::factory()->create();
        $toAccount = BankAccount::factory()->create();
        $transferAmount = 50;

        $this->json('POST', route('amount.transfer'), [
            'from' => $fromAccount->id,
            'to' => $toAccount->id,
            'amount' => $transferAmount
        ])
        ->assertOk()
        ->assertJsonFragment([
            'success' => true,
        ]);

        $this->assertDatabaseHas('bank_accounts', [
            'user_id' => $fromAccount->id,
            'balance' => $fromAccount->balance - $transferAmount
        ]);

        $this->assertDatabaseHas('bank_accounts', [
            'user_id' => $toAccount->id,
            'balance' => $toAccount->balance + $transferAmount
        ]);

        // it should record history for $fromAccount
        $this->assertDatabaseHas('account_histories', [
            'bank_account_id' => $fromAccount->id,
            'amount' => $transferAmount,
            'balance' => $fromAccount->balance - $transferAmount,
        ]);

        // it should record history for $toAccount
        $this->assertDatabaseHas('account_histories', [
            'bank_account_id' => $toAccount->id,
            'amount' => $transferAmount,
            'balance' => $toAccount->balance + $transferAmount
        ]);
    }
}