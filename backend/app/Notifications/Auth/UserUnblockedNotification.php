<?php

namespace App\Notifications\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class UserUnblockedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('تم تفعيل حسابك - ' . config('app.name'))
            ->greeting('مرحباً ' . $notifiable->name . '!')
            ->line('نود إعلامك أنه تم تفعيل حسابك مرة أخرى في منصتنا.')
            ->line('يمكنك الآن تسجيل الدخول والاستفادة من جميع خدماتنا كالمعتاد.')
            ->action('تسجيل الدخول', url('/login'))
            ->line('نرحب بك مرة أخرى ونتطلع لتقديم أفضل الخدمات لك.')
            ->salutation('مع تمنياتنا بتجربة ممتعة،<br>فريق ' . config('app.name'));
    }

    /**
     * Get the array representation for the database.
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'تم تفعيل حسابك',
            'message' => 'تم تفعيل حسابك مرة أخرى. يمكنك الآن الوصول إلى جميع خدماتنا.',
            'icon' => 'fas fa-user-check',
            'url' => url('/login'),
            'type' => 'account_activated'
        ];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'تم تفعيل حسابك',
            'message' => 'تم تفعيل حسابك مرة أخرى. يمكنك الآن الوصول إلى جميع خدماتنا.',
            'url' => url('/login'),
            'type' => 'account_activated'
        ];
    }
}