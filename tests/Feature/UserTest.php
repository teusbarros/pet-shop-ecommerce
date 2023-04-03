<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{

    public function test_user_can_see_his_data(): void
    {
        $token = $this->user->refresh()->token->unique_id;
        $user = $this->user;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->json('get', '/api/v1/user');

        $response->assertStatus(200);
        $response->assertJsonPath('data.first_name', $user->first_name);
    }

    public function test_user_can_edit_his_data(): void
    {
        $token = $this->user->refresh()->token->unique_id;
        $dataForm = [
            'first_name' => "New fist name",
            'last_name' => "New last name",
            'email' => "mail@mail.com",
            'password' => "12345678",
            'password_confirmation' => "12345678",
            'address' => "User new address",
            'phone_number' => "9090909909909",
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->json('put', '/api/v1/user/edit', $dataForm);

        $response->assertStatus(200);
        $response->assertJsonPath('data.first_name', $dataForm['first_name']);
    }

    public function test_reset_password_token_returns_valid_token_to_valid_email(): void
    {

        $response = $this->json('post', '/api/v1/user/forgot-password', ['email' => $this->user->email]);

        $response->assertStatus(200);
        $response->assertJsonPath('data.reset_token', $this->user->getNewResetPasswordToken());
    }

    public function test_reset_password_token_returns_404_to_invalid_email(): void
    {

        $response = $this->json('post', '/api/v1/user/forgot-password', ['email' =>'mail@mail.com']);

        $response->assertStatus(404);
        $response->assertJsonFragment(['error' => 'Invalid email']);
    }
}
