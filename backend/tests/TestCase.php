<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();
        
        // إعدادات إضافية للاختبارات
        $this->withoutExceptionHandling();
    }

    /**
     * Create an admin user for testing
     */
    protected function createAdminUser(array $attributes = [])
    {
        return \App\Models\User::factory()->create(array_merge([
            'role' => 'admin',
            'status' => 'active'
        ], $attributes));
    }

    /**
     * Create a regular user for testing
     */
    protected function createUser(array $attributes = [])
    {
        return \App\Models\User::factory()->create(array_merge([
            'role' => 'visitor',
            'status' => 'active'
        ], $attributes));
    }

    /**
     * Generate authentication headers for API requests
     */
    protected function authHeaders($user = null)
    {
        $user = $user ?: $this->createUser();
        $token = $user->createToken('test-token')->plainTextToken;

        return [
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ];
    }
}