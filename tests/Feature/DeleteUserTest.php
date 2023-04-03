<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteUserTest extends TestCase
{

    public function test_admin_user_cannot_be_deleted(): void
    {
        $token = $this->admin->refresh()->token->unique_id;
        $admin2 = $this->createUser(true);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->json('delete', '/api/v1/admin/user-delete/' . $admin2->uuid);

        $response->assertStatus(401);
        $response->assertJsonFragment(['error' => 'Unauthorized: Not enough privileges']);
    }

    public function test_user_can_be_deleted_by_admin(): void
    {
        $token = $this->admin->refresh()->token->unique_id;
        $user = $this->user;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->json('delete', '/api/v1/admin/user-delete/'. $user->uuid);

        $response->assertStatus(200);
        $response->assertJsonFragment(['error' => null]);
    }

}
