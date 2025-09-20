<?php

namespace App\Notifications\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class WelcomeNotification extends Notification
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
            ->subject('مرحباً بك في ' . config('app.name'))
            ->greeting('مرحباً ' . $notifiable->name . '!')
            ->line('نحن سعداء بانضمامك إلى منصتنا.')
            ->line('يمكنك الآن الاستفادة من جميع ميزاتنا المتاحة.')
            ->action('ابدأ رحلتك', url('/'))
            ->line('إذا كان لديك أي استفسار، لا تتردد في التواصل معنا.')
            ->salutation('مع تمنياتنا بالتوفيق،<br>فريق ' . config('app.name'));
    }

    /**
     * Get the array representation for the database.
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'مرحباً بك في ' . config('app.name'),
            'message' => 'تم تسجيل حسابك بنجاح. نحن سعداء بانضمامك إلينا!',
            'icon' => 'fas fa-user-plus',
            'url' => url('/profile'),
            'type' => 'welcome'
        ];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'مرحباً بك في ' . config('app.name'),
            'message' => 'تم تسجيل حسابك بنجاح. نحن سعداء بانضمامك إلينا!',
            'url' => url('/profile'),
            'type' => 'welcome'
        ];
    }
}