<?php

namespace Tests\Unit\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * test_user_creation
     */
    public function test_user_creation()
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'visitor',
            'status' => 'active'
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'name' => 'Test User'
        ]);
    }

    /**
     * test_user_is_admin
     */
    public function test_user_is_admin()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'visitor']);

        $this->assertTrue($admin->isAdmin());
        $this->assertFalse($user->isAdmin());
    }

    /**
     * test_user_is_active
     */
    public function test_user_is_active()
    {
        $activeUser = User::factory()->create(['status' => 'active']);
        $blockedUser = User::factory()->create(['status' => 'blocked']);

        $this->assertTrue($activeUser->isActive());
        $this->assertFalse($blockedUser->isActive());
    }

    /**
     * test_user_is_blocked
     */
    public function test_user_is_blocked()
    {
        $activeUser = User::factory()->create(['status' => 'active']);
        $blockedUser = User::factory()->create(['status' => 'blocked']);

        $this->assertFalse($activeUser->isBlocked());
        $this->assertTrue($blockedUser->isBlocked());
    }

    /**
     * test_user_block_method
     */
    public function test_user_block_method()
    {
        $user = User::factory()->create(['status' => 'active']);
        
        $user->block();
        
        $this->assertEquals('blocked', $user->fresh()->status);
    }

    /**
     * test_user_unblock_method
     */
    public function test_user_unblock_method()
    {
        $user = User::factory()->create(['status' => 'blocked']);
        
        $user->unblock();
        
        $this->assertEquals('active', $user->fresh()->status);
    }

    /**
     * test_user_avatar_url
     */
    public function test_user_avatar_url()
    {
        $user = User::factory()->create();
        
        $this->assertStringContainsString('ui-avatars.com', $user->avatar_url);
    }

    /**
     * test_user_display_name
     */
    public function test_user_display_name()
    {
        $userWithName = User::factory()->create(['name' => 'Test User']);
        $userWithoutName = User::factory()->create(['name' => null]);
        
        $this->assertEquals('Test User', $userWithName->display_name);
        $this->assertEquals(explode('@', $userWithoutName->email)[0], $userWithoutName->display_name);
    }

    /**
     * test_user_soft_delete
     */
    public function test_user_soft_delete()
    {
        $user = User::factory()->create();
        
        $user->delete();
        
        $this->assertSoftDeleted('users', ['id' => $user->id]);
    }

    /**
     * test_user_scope_active
     */
    public function test_user_scope_active()
    {
        User::factory()->create(['status' => 'active']);
        User::factory()->create(['status' => 'blocked']);
        
        $activeUsers = User::active()->get();
        
        $this->assertCount(1, $activeUsers);
        $this->assertEquals('active', $activeUsers->first()->status);
    }

    /**
     * test_user_scope_admins
     */
    public function test_user_scope_admins()
    {
        User::factory()->create(['role' => 'admin']);
        User::factory()->create(['role' => 'visitor']);
        
        $admins = User::admins()->get();
        
        $this->assertCount(1, $admins);
        $this->assertEquals('admin', $admins->first()->role);
    }
}