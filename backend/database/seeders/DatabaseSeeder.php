<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // استدعاء الـ Seeders التي تحتاج إلى تنفيذها
        $this->call([
            AdminUserSeeder::class,
            ProjectSeeder::class,
            SkillSeeder::class,
            ContactSeeder::class, // إضافة ContactSeeder هنا
        ]);
    }
}
