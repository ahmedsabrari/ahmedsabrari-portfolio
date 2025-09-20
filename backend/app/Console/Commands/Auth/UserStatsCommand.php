<?php

namespace App\Console\Commands\Auth;

use App\Models\User;
use Illuminate\Console\Command;

class UserStatsCommand extends Command
{
    protected $signature = 'user:stats';

    protected $description = 'عرض إحصائيات المستخدمين';

    public function handle()
    {
        $totalUsers = User::count();
        $activeUsers = User::where('status', 'active')->count();
        $blockedUsers = User::where('status', 'blocked')->count();
        $adminUsers = User::where('role', 'admin')->count();
        $visitorUsers = User::where('role', 'visitor')->count();

        $this->info('إحصائيات المستخدمين:');
        $this->line('إجمالي المستخدمين: ' . $totalUsers);
        $this->line('المستخدمين النشطين: ' . $activeUsers);
        $this->line('المستخدمين المحظورين: ' . $blockedUsers);
        $this->line('المسؤولين: ' . $adminUsers);
        $this->line('الزوار: ' . $visitorUsers);

        // عرض جدول بالمستخدمين المسؤولين
        $admins = User::where('role', 'admin')->get(['name', 'email', 'status', 'created_at']);
        
        if ($admins->count() > 0) {
            $this->info("\nالمسؤولين:");
            $this->table(
                ['الاسم', 'البريد الإلكتروني', 'الحالة', 'تاريخ الإنشاء'],
                $admins->map(function ($admin) {
                    return [
                        $admin->name,
                        $admin->email,
                        $admin->status === 'active' ? '🟢 نشط' : '🔴 محظور',
                        $admin->created_at->format('Y-m-d')
                    ];
                })
            );
        }

        return 0;
    }
}