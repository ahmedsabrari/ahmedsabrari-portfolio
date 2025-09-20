<?php

namespace App\Console\Commands\Auth;

use App\Models\User;
use Illuminate\Console\Command;

class UserBlockCommand extends Command
{
    protected $signature = 'user:block {email : البريد الإلكتروني للمستخدم} {--reason= : سبب الحظر}';

    protected $description = 'حظر مستخدم عن طريق البريد الإلكتروني';

    public function handle()
    {
        $email = $this->argument('email');
        $reason = $this->option('reason');

        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error('المستخدم غير موجود');
            return 1;
        }

        if ($user->email === 'admin@gmail.com') {
            $this->error('لا يمكن حظر المستخدم المسؤول الرئيسي');
            return 1;
        }

        if ($user->status === 'blocked') {
            $this->error('المستخدم محظور بالفعل');
            return 1;
        }

        $user->update(['status' => 'blocked']);

        // إرسال إشعار الحظر إذا كان موجوداً
        if (class_exists(\App\Notifications\Auth\UserBlockedNotification::class)) {
            $user->notify(new \App\Notifications\Auth\UserBlockedNotification($reason));
        }

        $this->info('تم حظر المستخدم بنجاح: ' . $user->email);
        if ($reason) {
            $this->line('السبب: ' . $reason);
        }

        return 0;
    }
}