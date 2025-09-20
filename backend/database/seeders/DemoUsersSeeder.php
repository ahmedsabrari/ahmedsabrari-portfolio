<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class DemoUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إنشاء أو استعادة دور الزائر
        $visitorRole = Role::firstOrCreate([
            'name' => 'visitor',
            'guard_name' => 'web'
        ], [
            'name' => 'visitor',
            'guard_name' => 'web'
        ]);

        // إنشاء مستخدمين تجريبيين
        $demoUsers = [
            [
                'name' => 'John Doe',
                'email' => 'john.doe@example.com',
                'password' => Hash::make('password123'),
                'role' => 'visitor',
                'status' => 'active',
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane.smith@example.com',
                'password' => Hash::make('password123'),
                'role' => 'visitor',
                'status' => 'active',
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ],
            [
                'name' => 'Mohammed Ahmed',
                'email' => 'mohammed.ahmed@example.com',
                'password' => Hash::make('password123'),
                'role' => 'visitor',
                'status' => 'blocked', // مستخدم محظور للتجربة
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ]
        ];

        foreach ($demoUsers as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                $userData
            );
            
            $user->assignRole($visitorRole);
        }

        $this->command->info('✅ تم إنشاء المستخدمين التجريبيين بنجاح!');
    }
}