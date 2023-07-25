<?php

namespace Tests\Unit;

use App\Models\Account;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountTest extends TestCase
{
    use RefreshDatabase;

    public function testDepositSuccess()
    {
        $user = User::factory()->create();
        $account = new Account();
        $account->create(['balance' => 500, "user_id" => $user->id]);
        $user->account()->save($account);

        $amount = 100;
        $response = $this->actingAs($user)->postJson('/api/v1/account/deposit', ['amount' => $amount]);

        $response->assertStatus(200);
    }

    public function testInvalidDepositAmount()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/v1/account/deposit', ['amount' => -100]);

        $response->assertStatus(400);
    }

    public function testWithdrawSuccess()
    {
        $user = User::factory()->create();
        $account = new Account();
        $account->create(['balance' => 500, "user_id" => $user->id]);
        $user->account()->save($account);

        $amount = 100;
        $response = $this->actingAs($user)->postJson('/api/v1/account/withdraw', ['amount' => $amount]);

        $response->assertStatus(200);
    }

    public function testInvalidWithdrawAmount()
    {
        $user = User::factory()->create();
        $account = new Account();
        $account->create(['balance' => 500, "user_id" => $user->id]);

        $user->account()->save($account);

        $amount = 1000;
        $response = $this->actingAs($user)->postJson('/api/v1/account/withdraw', ['amount' => $amount]);

        $response->assertStatus(400);
    }

    public function testInsufficientBalanceForWithdraw()
    {
        $user = User::factory()->create();
        $account = new Account();
        $account->create(['balance' => -400, "user_id" => $user->id]);
        $user->account()->save($account);

        $amount = 200;
        $response = $this->actingAs($user)->postJson('/api/v1/account/withdraw', ['amount' => $amount]);

        $response->assertStatus(400);
    }

    public function testBalance()
    {
        $user = User::factory()->create();

        $account = new Account();
        $account->create(['balance' => 500, "user_id" => $user->id]);

        $user->account()->save($account);

        $response = $this->actingAs($user)->getJson('/api/v1/account/balance');

        $response->assertStatus(200);
    }
}
