<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class JWTServiceTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_cannot_access_protected_route_without_token(): void
    {
        $token = '';

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->json('get', '/api/v1/admin/user-listing');

        $response->assertStatus(422);
        $response->assertJsonFragment(['error' => 'Invalid token']);
    }

    public function test_cannot_access_protected_route_with_expired_token(): void
    {
        $token = $this->admin->refresh()->token->unique_id;

        $this->travelTo(now()->addDay());
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->json('get', '/api/v1/admin/user-listing');


        $response->assertStatus(401);
        $response->assertJsonFragment(['error' => 'Expired token']);
    }

    public function test_can_access_protected_route_with_valid_token(): void
    {
        $token = $this->admin->refresh()->token->unique_id;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->json('get', '/api/v1/admin/user-listing');

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
    }

    public function test_admin_cannot_access_user_route(): void
    {
        $token = $this->admin->refresh()->token->unique_id;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->json('get', '/api/v1/user');

        $response->assertStatus(403);
        $response->assertJsonFragment(['error' => 'Forbidden']);
    }

    public function test_user_cannot_access_admin_route(): void
    {
        $token = $this->user->refresh()->token->unique_id;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->json('get', '/api/v1/admin/user-listing');

        $response->assertStatus(403);
        $response->assertJsonFragment(['error' => 'Forbidden']);
    }

}
