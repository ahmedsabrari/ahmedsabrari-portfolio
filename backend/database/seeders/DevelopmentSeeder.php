<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DevelopmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إنشاء 10 مستخدمين عشوائيين للاختبار
        User::factory(10)->create([
            'role' => 'visitor',
            'status' => 'active',
        ]);

        // إنشاء 5 مستخدمين محظورين للاختبار
        User::factory(5)->create([
            'role' => 'visitor',
            'status' => 'blocked',
        ]);

        $this->command->info('✅ تم إنشاء المستخدمين التجريبيين الإضافيين بنجاح!');
    }
}