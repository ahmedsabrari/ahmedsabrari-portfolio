<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // إضافة هذا السطر
use Illuminate\Support\Str;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('skills')->insert([
            [
                'name' => 'Laravel',
                'category' => 'backend',
                'level' => 5,
                'color' => '#FF8C00', // اللون البرتقالي
                'icon' => 'laravel-icon.png',
                'is_featured' => true,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Vue.js',
                'category' => 'frontend',
                'level' => 4,
                'color' => '#42b883', // اللون الأخضر
                'icon' => 'vuejs-icon.png',
                'is_featured' => true,
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'JavaScript',
                'category' => 'frontend',
                'level' => 5,
                'color' => '#f7df1e', // اللون الأصفر
                'icon' => 'javascript-icon.png',
                'is_featured' => false,
                'sort_order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'PHP',
                'category' => 'backend',
                'level' => 5,
                'color' => '#777bb3', // اللون الأزرق
                'icon' => 'php-icon.png',
                'is_featured' => true,
                'sort_order' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'MySQL',
                'category' => 'backend',
                'level' => 4,
                'color' => '#00618C', // اللون الأزرق الداكن
                'icon' => 'mysql-icon.png',
                'is_featured' => false,
                'sort_order' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
