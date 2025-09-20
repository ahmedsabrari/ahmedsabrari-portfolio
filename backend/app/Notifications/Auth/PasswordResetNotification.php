<?php

namespace App\Notifications\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\ResetPassword as BaseResetPassword;

class PasswordResetNotification extends BaseResetPassword
{
    use Queueable;

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('إعادة تعيين كلمة المرور - ' . config('app.name'))
            ->greeting('مرحباً ' . $notifiable->name)
            ->line('تلقينا طلباً لإعادة تعيين كلمة المرور لحسابك.')
            ->action('إعادة تعيين كلمة المرور', url(config('app.url').route('password.reset', [
                'token' => $this->token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false)))
            ->line('ستنتهي صلاحية رابط إعادة التعيين خلال '.config('auth.passwords.'.config('auth.defaults.passwords').'.expire').' دقيقة.')
            ->line('إذا لم تطلب إعادة تعيين كلمة المرور، لا داعي لاتخاذ أي إجراء.')
            ->salutation('مع التحية،<br>فريق ' . config('app.name'));
    }
}