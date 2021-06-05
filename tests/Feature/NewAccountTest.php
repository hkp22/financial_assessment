<?php

namespace Tests\Feature;

use App\Models\BankAccount;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewAccountTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function it_may_create_a_new_account()
    {
        $customer = User::factory()->create();

        $this->json('POST', route('new.account', $customer), [
            'amount' => 100
        ])
        ->assertOk();

        $this->assertDatabaseHas('bank_accounts', [
            'user_id' => $customer->id,
            'balance' => 100
        ]);

        $this->assertDatabaseHas('account_histories', [
            'bank_account_id' => BankAccount::first()->id,
            'amount' => 100,
            'balance' => 100
        ]);
    }

    /** @test **/
    public function new_account_deposit_amount_is_required()
    {
        $customer = User::factory()->create();

        $this->json('POST', route('new.account', $customer))
            ->assertStatus(422)
            ->assertJsonValidationErrors('amount');
    }

    /** @test **/
    public function new_account_deposit_amount_should_not_be_below_zero()
    {
        $customer = User::factory()->create();

        $this->json('POST', route('new.account', $customer), [
            'amount' => -1
        ])
        ->assertStatus(422)
        ->assertJsonValidationErrors('amount');
    }
}