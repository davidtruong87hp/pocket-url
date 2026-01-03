<?php

namespace Tests\Feature\Api\Auth;

use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public const API_ENDPOINT = '/api/register';

    public function test_register_success()
    {
        $password = $this->faker->password(8);

        $this->postJson(self::API_ENDPOINT, [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => $password,
            'password_confirmation' => $password,
        ])->assertJsonStructure([
            'user',
            'token',
        ]);
    }

    public function test_register_failed_name_not_provided()
    {
        $this->postJson(self::API_ENDPOINT, [
            'email' => $this->faker->safeEmail(),
            'password' => $this->faker->password(),
            'password_confirmation' => $this->faker->password(),
        ])->assertJsonValidationErrors('name');
    }

    public function test_register_failed_email_not_provided()
    {
        $this->postJson(self::API_ENDPOINT, [
            'name' => $this->faker->name(),
            'password' => $this->faker->password(),
            'password_confirmation' => $this->faker->password(),
        ])->assertJsonValidationErrors('email');
    }

    public function test_register_failed_password_not_provided()
    {
        $this->postJson(self::API_ENDPOINT, [
            'name' => $this->faker->name(),
            'email' => $this->faker->safeEmail(),
        ])->assertJsonValidationErrors('password');
    }

    public function test_register_failed_password_not_match()
    {
        $this->postJson(self::API_ENDPOINT, [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => $this->faker->password(),
            'password_confirmation' => $this->faker->password(),
        ])->assertJsonValidationErrors('password');
    }

    public function test_register_failed_password_too_short()
    {
        $password = $this->faker->password(6, 7);

        $this->postJson(self::API_ENDPOINT, [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => $password,
            'password_confirmation' => $password,
        ])->assertJsonValidationErrors('password');
    }

    public function test_register_failed_email_exists()
    {
        $email = $this->faker->email();
        $password = $this->faker->password(8);

        UserFactory::new()->create([
            'email' => $email,
            'password' => $password,
        ]);

        $this->postJson(self::API_ENDPOINT, [
            'name' => $this->faker->name(),
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $password,
        ])->assertJsonValidationErrors('email');
    }
}
