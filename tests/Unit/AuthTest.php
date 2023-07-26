<?php

namespace tests\Unit;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Enums\HttpStatusCodes;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Artisan;
use Tests\CreatesApplication;
use Tests\TestCase;
use Illuminate\Support\Facades\Schema;
use Laravel\Passport\ClientRepository;

class AuthTest extends TestCase
{
    /**
     * A basic test example.
     */
    use CreatesApplication;
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('passport:install');


        if (Schema::hasTable('oauth_clients')) {
            resolve(ClientRepository::class)->createPersonalAccessClient(
                null, 'Personal Access Token', config('app.url')
            );
        }
    }

    public function test_signup(): void
    {
        $this->json('Post', route('auth.signup'))->assertStatus(HttpStatusCodes::UnprocessableEnttiy);

        $data = [
            "name" => "test",
            "password" => "Test1234",
            "confirmation_password" => "Test1234",
            "email" => "test@gmail.com"
        ];

        $this->json('Post', route('auth.signup'), $data)->assertStatus(HttpStatusCodes::Accepted);


        $this->json('Post', route('auth.signup'), $data)->assertStatus(HttpStatusCodes::UnprocessableEnttiy);


    }

    /*public function test_login(): void
    {
        $this->json('Post', route('auth.login'))->assertStatus(HttpStatusCodes::UnprocessableEnttiy);


        User::factory(1)->create([
            'email' => 'test@gmail.com',
            'password' => bcrypt('secret')
        ])->first();


        $response = $this->json('POST', route('auth.login'), [
            'email' => 'test@gmail.com',
            'password' => 'secret',
        ]);

        $response->assertStatus(200);

        //$accessToken = $response->json('access_token');

        $this->assertTrue(User::where("email", "test@gmail.com")->exists());

        $data = [
            "email" => "test@gmail.com",
            "password" => "wrongPassword123"
        ];

        $this->json('Post', route('auth.login'), $data)->assertStatus(HttpStatusCodes::BadRequest);

    }*/
}
