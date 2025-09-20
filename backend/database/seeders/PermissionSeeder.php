<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // مسح البيانات القديمة
        DB::table('role_has_permissions')->delete();
        DB::table('model_has_roles')->delete();
        DB::table('model_has_permissions')->delete();
        DB::table('permissions')->delete();
        DB::table('roles')->delete();

        // إعادة تعيين التتابع
        DB::statement('ALTER TABLE roles AUTO_INCREMENT = 1');
        DB::statement('ALTER TABLE permissions AUTO_INCREMENT = 1');

        // إنشاء الأدوار
        $adminRole = Role::create(['name' => 'admin', 'guard_name' => 'web']);
        $visitorRole = Role::create(['name' => 'visitor', 'guard_name' => 'web']);

        // إنشاء الصلاحيات
        $permissions = [
            // صلاحيات إدارة المستخدمين
            'view-users', 'create-users', 'edit-users', 'delete-users', 'block-users',
            
            // صلاحيات إدارة المشاريع
            'view-projects', 'create-projects', 'edit-projects', 'delete-projects',
            
            // صلاحيات إدارة المهارات
            'view-skills', 'create-skills', 'edit-skills', 'delete-skills',
            
            // صلاحيات إدارة الاتصالات
            'view-contacts', 'create-contacts', 'edit-contacts', 'delete-contacts',
            
            // صلاحيات النظام
            'access-admin-panel', 'manage-settings'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'web']);
        }

        // منح جميع الصلاحيات لدور المسؤول
        $adminRole->givePermissionTo(Permission::all());

        // منح صلاحيات محدودة للزائر
        $visitorRole->givePermissionTo([
            'view-projects', 'view-skills'
        ]);

        $this->command->info('✅ تم إنشاء الأدوار والصلاحيات بنجاح!');
    }
}