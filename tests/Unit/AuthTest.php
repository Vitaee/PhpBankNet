<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test user registration (signup).
     *
     * @return void
     */
    public function test_user_registration()
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => 'secret123',
            'confirmation_password' => 'secret123'
        ];

        $response = $this->postJson(route('auth.signup'), $userData);

        $response->assertStatus(201);


        $this->assertDatabaseHas('users', [
            'name' => $userData['name'],
            'email' => $userData['email'],
        ]);
    }

    /**
     * Test user login.
     *
     * @return void
     */
    public function test_user_login()
    {
        $user = new User();
        $password_salt = "test";


        $user->create([
            'name' => 'test',
            'email' => 'john.doe@example.com',
            'password' => Hash::make('secret123'),
        ]);

        $credentials = [
            'email' => 'john.doe@example.com',
            'password' => 'secret123',
        ];

        $response = $this->postJson(route('auth.login'), $credentials);

        $response->assertStatus(200);

    }
}
