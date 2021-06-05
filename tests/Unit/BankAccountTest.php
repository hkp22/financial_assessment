<?php

namespace Tests\Unit;

use App\Models\BankAccount;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BankAccountTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function it_may_withdraw_amount()
    {
        $account = BankAccount::factory()->create([
            'balance' => $initialBalance = 200
        ]);
        $account->withdrawAmount($withdrawAmount = 50, 'withdraw test');

        $this->assertEquals(
            $initialBalance - $withdrawAmount,
            $account->fresh()->balance
        );

        $this->assertDatabaseHas('account_histories', [
            'bank_account_id' => $account->id,
            'amount' => $withdrawAmount,
            'balance' => $account->balance,
        ]);
    }

    /** @test **/
    public function it_may_add_amount()
    {
        $account = BankAccount::factory()->create([
            'balance' => $initialBalance = 200
        ]);

        $account->addAmount($depositAmount = 50, 'deposit amount test');

        $this->assertEquals(
            $initialBalance + $depositAmount,
            $account->fresh()->balance
        );

        $this->assertDatabaseHas('account_histories', [
            'bank_account_id' => $account->id,
            'amount' => $depositAmount,
            'balance' => $account->balance,
        ]);
    }
}