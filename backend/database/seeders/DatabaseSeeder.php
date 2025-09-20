<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // تعطير عمليات الإدراج الجماعي لتحسين الأداء
        DB::disableQueryLog();
        $this->call([
            PermissionSeeder::class,      // بادئ الصلاحيات والأدوار
            AdminUserSeeder::class,       // بادئ المستخدم المسؤول
            DemoUsersSeeder::class,       // بادئ المستخدمين التجريبيين

            ProjectSeeder::class,
            SkillSeeder::class,
            ContactSeeder::class, // إضافة ContactSeeder هنا
        ]);

        // في بيئة التطوير، إنشاء بيانات تجريبية إضافية
        if (app()->environment('local')) {
            $this->call([
                DevelopmentSeeder::class, // بادئ بيانات التطوير
            ]);
        }

        $this->command->info('✅ تم تهيئة قاعدة البيانات بنجاح!');
        $this->command->info('👤 المستخدم المسؤول: admin@gmail.com / admin1234');
    }
}
