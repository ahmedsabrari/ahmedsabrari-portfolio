<?php

namespace App\Console\Commands\Auth;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateAdminCommand extends Command
{
    protected $signature = 'make:admin 
                            {--name= : اسم المستخدم}
                            {--email= : البريد الإلكتروني}
                            {--password= : كلمة المرور}';

    protected $description = 'إنشاء مستخدم مسؤول جديد';

    public function handle()
    {
        $name = $this->option('name') ?? $this->ask('أدخل اسم المستخدم');
        $email = $this->option('email') ?? $this->ask('أدخل البريد الإلكتروني');
        $password = $this->option('password') ?? $this->secret('أدخل كلمة المرور');

        // التحقق من صحة البيانات
        $validator = Validator::make([
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ], [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
        ]);

        if ($validator->fails()) {
            $this->error('بيانات غير صالحة:');
            foreach ($validator->errors()->all() as $error) {
                $this->error('- ' . $error);
            }
            return 1;
        }

        // إنشاء المستخدم المسؤول
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'role' => 'admin',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $this->info('تم إنشاء المستخدم المسؤول بنجاح!');
        $this->line('الاسم: ' . $user->name);
        $this->line('البريد الإلكتروني: ' . $user->email);
        $this->line('الدور: ' . $user->role);

        return 0;
    }
}