<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // التحقق من عدم وجود المستخدم المسؤول مسبقاً
        if (User::where('email', 'admin@gmail.com')->exists()) {
            $this->command->info('⚠️  المستخدم المسؤول موجود مسبقاً، تم تخطي الإنشاء.');
            return;
        }

        // إنشاء أو استعادة دور المسؤول
        $adminRole = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web'
        ], [
            'name' => 'admin',
            'guard_name' => 'web'
        ]);

        // إنشاء المستخدم المسؤول
        $admin = User::create([
            'name' => 'Ahmed Sabrari',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin1234'),
            'role' => 'admin',
            'status' => 'active',
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ]);

        // تعيين دور المسؤول للمستخدم
        $admin->assignRole($adminRole);

        // إنشاء مستخدم مسؤول إضافي للنسخ الاحتياطي
        $backupAdmin = User::create([
            'name' => 'Backup Admin',
            'email' => 'backup.admin@gmail.com',
            'password' => Hash::make('backup1234'),
            'role' => 'admin',
            'status' => 'active',
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ]);

        $backupAdmin->assignRole($adminRole);

        $this->command->info('✅ تم إنشاء المستخدمين المسؤولين بنجاح!');
        $this->command->info('👤 المسؤول الرئيسي: admin@gmail.com / admin1234');
        $this->command->info('👤 المسؤول الاحتياطي: backup.admin@gmail.com / backup1234');
    }
}