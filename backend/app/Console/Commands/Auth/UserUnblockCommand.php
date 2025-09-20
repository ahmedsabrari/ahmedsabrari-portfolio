<?php

namespace App\Console\Commands\Auth;

use App\Models\User;
use Illuminate\Console\Command;

class UserUnblockCommand extends Command
{
    protected $signature = 'user:unblock {email : البريد الإلكتروني للمستخدم}';

    protected $description = 'فك حظر مستخدم عن طريق البريد الإلكتروني';

    public function handle()
    {
        $email = $this->argument('email');

        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error('المستخدم غير موجود');
            return 1;
        }

        if ($user->status === 'active') {
            $this->error('المستخدم مفعل بالفعل');
            return 1;
        }

        $user->update(['status' => 'active']);

        // إرسال إشعار فك الحظر إذا كان موجوداً
        if (class_exists(\App\Notifications\Auth\UserUnblockedNotification::class)) {
            $user->notify(new \App\Notifications\Auth\UserUnblockedNotification());
        }

        $this->info('تم فك حظر المستخدم بنجاح: ' . $user->email);

        return 0;
    }
}