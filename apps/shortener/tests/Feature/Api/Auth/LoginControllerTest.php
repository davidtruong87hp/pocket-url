<?php

namespace Tests\Feature\Api\Auth;

use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;

    public const API_ENDPOINT = '/api/login';

    public function test_login_success()
    {
        $user = UserFactory::new()->create();

        $this->postJson(self::API_ENDPOINT, [
            'email' => $user->email,
            'password' => 'password',
        ])->assertJsonStructure([
            'token',
        ]);
    }

    public function test_login_failed_email_not_provided()
    {
        $this->postJson(self::API_ENDPOINT, [
            'password' => 'password',
        ])->assertJsonValidationErrors('email');
    }

    public function test_login_failed_password_not_provided()
    {
        $this->postJson(self::API_ENDPOINT, [
            'email' => 'email',
        ])->assertJsonValidationErrors('password');
    }

    public function test_login_failed_invalid_email()
    {
        $this->postJson(self::API_ENDPOINT, [
            'email' => 'email',
            'password' => 'password',
        ])->assertJsonValidationErrors('email');
    }

    public function test_login_failed_user_not_found()
    {
        $user = UserFactory::new()->create();

        $this->postJson(self::API_ENDPOINT, [
            'email' => $user->email,
            'password' => $user->password.'1',
        ])->assertJsonStructure([
            'message',
        ]);
    }
}
