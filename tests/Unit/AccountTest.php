<?php

namespace Tests\Unit;

use App\Models\Account;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Laravel\Passport\Passport;


class AccountTest extends TestCase
{
    use RefreshDatabase;


    public function test_deposit_success()
    {
        $user = User::factory()->create();
        Passport::actingAs( $user, ['api']);
        $account = new Account();
        $account->create(['balance' => 500, "user_id" => $user->id]);
        $user->account()->save($account);

        $amount = 100;
        $response = $this->actingAs($user)->postJson('/api/v1/account/deposit', ['amount' => $amount]);

        $response->assertStatus(200);
    }

    public function test_invalid_deposit_amount()
    {
        $user = User::factory()->create();
        Passport::actingAs( $user, ['api']);

        $response = $this->actingAs($user)->postJson('/api/v1/account/deposit', ['amount' => -100]);

        $response->assertStatus(422);
    }

    public function test_withdraw_success()
    {
        $user = User::factory()->create();
        Passport::actingAs( $user, ['api']);
        $account = new Account();
        $account->create(['balance' => 500, "user_id" => $user->id]);
        $user->account()->save($account);

        $amount = 100;
        $response = $this->actingAs($user)->postJson('/api/v1/account/withdraw', ['amount' => $amount]);

        $response->assertStatus(200);
    }

    public function test_invalid_withdraw_amount()
    {
        $user = User::factory()->create();
        Passport::actingAs( $user, ['api']);
        $account = new Account();
        $account->create(['balance' => 500, "user_id" => $user->id]);

        $user->account()->save($account);

        $amount = 1000;
        $response = $this->actingAs($user)->postJson('/api/v1/account/withdraw', ['amount' => $amount]);

        $response->assertStatus(422);
    }

    public function test_insufficient_balance_for_withdraw()
    {
        $user = User::factory()->create();
        Passport::actingAs( $user, ['api']);
        $account = new Account();
        $account->create(['balance' => -400, "user_id" => $user->id]);
        $user->account()->save($account);

        $amount = 200;
        $response = $this->actingAs($user)->postJson('/api/v1/account/withdraw', ['amount' => $amount]);

        $response->assertStatus(400);
    }

    public function test_balance()
    {
        $user = User::factory()->create();
        Passport::actingAs( $user, ['api']);

        $account = new Account();
        $account->create(['balance' => 500, "user_id" => $user->id]);

        $user->account()->save($account);

        $response = $this->actingAs($user)->getJson('/api/v1/account/balance');

        $response->assertStatus(200);
    }
}
