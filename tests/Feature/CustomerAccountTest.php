<?php

namespace Tests\Feature;

use App\Models\BankAccount;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerAccountTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function it_may_fetch_customer_account_balance()
    {
        $account = BankAccount::factory()->create();

        $this->json('GET', route('account.balance', $account))
             ->assertOk()
             ->assertJsonFragment([
                 'account' => $account->id,
                 'balance' => $account->balance
             ]);
    }

    /** @test **/
    public function it_may_fetch_account_history()
    {
        $customer = User::factory()->create();

        $this->json('POST', route('new.account', $customer), [
            'amount' => 100
        ]);

        $account = $customer->accounts()->first();

        $this->json('GET', route('account.history', $customer))
            ->assertOk()
            ->assertJsonPath('data.0.bank_account_id', (string) $account->id)
            ->assertJsonStructure([
                'data' => [
                    [
                        'id',
                        'bank_account_id',
                        'description',
                        'amount',
                        'balance'
                    ]
                ]
            ]);
    }
}