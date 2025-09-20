<?php

namespace Tests\Unit\Auth;

use App\Models\User;
use App\Http\Services\Auth\AuthService;
use App\Http\Services\Auth\UserService;
use App\Repositories\Contracts\Auth\AuthRepositoryInterface;
use App\Repositories\Contracts\Auth\UserRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Mockery;
use Tests\TestCase;

class ServicesTest extends TestCase
{
    use RefreshDatabase;

    protected $authRepository;
    protected $userRepository;
    protected $authService;
    protected $userService;

    protected function setUp(): void
    {
        parent::setUp();

        // Mock the repositories
        $this->authRepository = Mockery::mock(AuthRepositoryInterface::class);
        $this->userRepository = Mockery::mock(UserRepositoryInterface::class);

        $this->app->instance(AuthRepositoryInterface::class, $this->authRepository);
        $this->app->instance(UserRepositoryInterface::class, $this->userRepository);

        $this->authService = app(AuthService::class);
        $this->userService = app(UserService::class);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * test_auth_service_registration
     */
    public function test_auth_service_registration()
    {
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'Password123!',
            'role' => 'visitor'
        ];

        $user = User::factory()->make($userData);
        
        $this->authRepository->shouldReceive('create')
            ->once()
            ->with(Mockery::on(function ($data) use ($userData) {
                return $data['name'] === $userData['name'] && 
                       $data['email'] === $userData['email'] &&
                       Hash::check($userData['password'], $data['password']);
            }))
            ->andReturn($user);

        $result = $this->authService->register($userData);

        $this->assertArrayHasKey('user', $result);
        $this->assertArrayHasKey('token', $result);
        $this->assertEquals($user->email, $result['user']->email);
    }

    /**
     * test_auth_service_login_success
     */
    public function test_auth_service_login_success()
    {
        $credentials = [
            'email' => 'test@example.com',
            'password' => 'Password123!'
        ];

        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('Password123!'),
            'status' => 'active'
        ]);

        $this->authRepository->shouldReceive('findByEmail')
            ->once()
            ->with('test@example.com')
            ->andReturn($user);

        $result = $this->authService->login($credentials);

        $this->assertArrayHasKey('user', $result);
        $this->assertArrayHasKey('token', $result);
        $this->assertEquals($user->email, $result['user']->email);
    }

    /**
     * test_auth_service_login_failure
     */
    public function test_auth_service_login_failure()
    {
        $credentials = [
            'email' => 'test@example.com',
            'password' => 'WrongPassword!'
        ];

        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('Password123!')
        ]);

        $this->authRepository->shouldReceive('findByEmail')
            ->once()
            ->with('test@example.com')
            ->andReturn($user);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('بيانات الاعتماد غير صحيحة');

        $this->authService->login($credentials);
    }

    /**
     * test_user_service_get_all_users
     */
    public function test_user_service_get_all_users()
    {
        $users = User::factory()->count(3)->create();
        
        $this->userRepository->shouldReceive('getQuery')
            ->once()
            ->andReturn(User::query());
            
        $this->userRepository->shouldReceive('count')
            ->andReturn(3);

        $result = $this->userService->getAllUsers();

        $this->assertCount(3, $result->items());
    }

    /**
     * test_user_service_block_user
     */
    public function test_user_service_block_user()
    {
        $user = User::factory()->create(['status' => 'active']);
        $updatedUser = clone $user;
        $updatedUser->status = 'blocked';

        $this->userRepository->shouldReceive('update')
            ->once()
            ->with($user, ['status' => 'blocked'])
            ->andReturn($updatedUser);

        $result = $this->userService->blockUser($user);

        $this->assertEquals('blocked', $result->status);
    }

    /**
     * test_user_service_unblock_user
     */
    public function test_user_service_unblock_user()
    {
        $user = User::factory()->create(['status' => 'blocked']);
        $updatedUser = clone $user;
        $updatedUser->status = 'active';

        $this->userRepository->shouldReceive('update')
            ->once()
            ->with($user, ['status' => 'active'])
            ->andReturn($updatedUser);

        $result = $this->userService->unblockUser($user);

        $this->assertEquals('active', $result->status);
    }

    /**
     * test_user_service_change_password
     */
    public function test_user_service_change_password()
    {
        $user = User::factory()->create([
            'password' => Hash::make('OldPassword123!')
        ]);

        $this->userRepository->shouldReceive('update')
            ->once()
            ->with($user, Mockery::on(function ($data) {
                return Hash::check('NewPassword123!', $data['password']);
            }))
            ->andReturn($user);

        $result = $this->userService->changePassword(
            $user,
            'OldPassword123!',
            'NewPassword123!'
        );

        $this->assertTrue($result);
    }

    /**
     * test_user_service_get_users_stats
     */
    public function test_user_service_get_users_stats()
    {
        User::factory()->create(['role' => 'admin', 'status' => 'active']);
        User::factory()->create(['role' => 'visitor', 'status' => 'active']);
        User::factory()->create(['role' => 'visitor', 'status' => 'blocked']);

        $this->userRepository->shouldReceive('count')
            ->andReturn(3);
            
        $this->userRepository->shouldReceive('countByStatus')
            ->with('active')
            ->andReturn(2);
            
        $this->userRepository->shouldReceive('countByStatus')
            ->with('blocked')
            ->andReturn(1);
            
        $this->userRepository->shouldReceive('countByRole')
            ->with('admin')
            ->andReturn(1);
            
        $this->userRepository->shouldReceive('countByRole')
            ->with('visitor')
            ->andReturn(2);

        $stats = $this->userService->getUsersStats();

        $this->assertEquals(3, $stats['total']);
        $this->assertEquals(2, $stats['active']);
        $this->assertEquals(1, $stats['blocked']);
        $this->assertEquals(1, $stats['admins']);
        $this->assertEquals(2, $stats['visitors']);
    }
}