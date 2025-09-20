<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $admin;
    protected $user;
    protected $adminToken;
    protected $userToken;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active'
        ]);

        $this->user = User::factory()->create([
            'role' => 'visitor',
            'status' => 'active'
        ]);

        $this->adminToken = $this->admin->createToken('authToken')->plainTextToken;
        $this->userToken = $this->user->createToken('authToken')->plainTextToken;
    }

    /**
     * test_admin_can_get_all_users
     */
    public function test_admin_can_get_all_users()
    {
        User::factory()->count(5)->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->adminToken
        ])->getJson('/api/users');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'users' => [
                    '*' => ['id', 'name', 'email', 'role', 'status']
                ],
                'pagination'
            ]);
    }

    /**
     * test_regular_user_cannot_get_all_users
     */
    public function test_regular_user_cannot_get_all_users()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->userToken
        ])->getJson('/api/users');

        $response->assertStatus(403);
    }

    /**
     * test_admin_can_block_user
     */
    public function test_admin_can_block_user()
    {
        $userToBlock = User::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->adminToken
        ])->postJson("/api/users/{$userToBlock->id}/block");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'تم حظر المستخدم بنجاح'
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $userToBlock->id,
            'status' => 'blocked'
        ]);
    }

    /**
     * test_admin_can_unblock_user
     */
    public function test_admin_can_unblock_user()
    {
        $userToUnblock = User::factory()->create(['status' => 'blocked']);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->adminToken
        ])->postJson("/api/users/{$userToUnblock->id}/unblock");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'تم فك حظر المستخدم بنجاح'
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $userToUnblock->id,
            'status' => 'active'
        ]);
    }

    /**
     * test_regular_user_cannot_block_users
     */
    public function test_regular_user_cannot_block_users()
    {
        $userToBlock = User::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->userToken
        ])->postJson("/api/users/{$userToBlock->id}/block");

        $response->assertStatus(403);
    }

    /**
     * test_user_can_view_own_profile
     */
    public function test_user_can_view_own_profile()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->userToken
        ])->getJson('/api/profile');

        $response->assertStatus(200)
            ->assertJson([
                'user' => [
                    'id' => $this->user->id,
                    'email' => $this->user->email
                ]
            ]);
    }

    /**
     * test_user_can_update_own_profile
     */
    public function test_user_can_update_own_profile()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->userToken
        ])->putJson('/api/profile', [
            'name' => 'Updated Name',
            'email' => $this->user->email
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'تم تحديث الملف الشخصي بنجاح'
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'name' => 'Updated Name'
        ]);
    }

    /**
     * test_user_can_change_password
     */
    public function test_user_can_change_password()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->userToken
        ])->postJson('/api/change-password', [
            'current_password' => 'password', // default factory password
            'new_password' => 'NewPassword123!',
            'new_password_confirmation' => 'NewPassword123!'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'تم تغيير كلمة المرور بنجاح'
            ]);
    }

    /**
     * test_admin_can_get_users_stats
     */
    public function test_admin_can_get_users_stats()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->adminToken
        ])->getJson('/api/users/stats');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'stats' => [
                    'total', 'active', 'blocked', 'admins', 'visitors'
                ]
            ]);
    }
}