<?php

namespace App\Notifications\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class UserBlockedNotification extends Notification
{
    use Queueable;

    protected $reason;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $reason = null)
    {
        $this->reason = $reason;
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
        $mailMessage = (new MailMessage)
            ->subject('تغيير في حالة حسابك - ' . config('app.name'))
            ->greeting('عزيزي/عزيزتي ' . $notifiable->name)
            ->line('نود إعلامك أنه تم تعطيل حسابك في منصتنا.');

        if ($this->reason) {
            $mailMessage->line('السبب: ' . $this->reason);
        }

        $mailMessage
            ->line('لن تتمكن من الوصول إلى حسابك حتى يتم فك الحظر من قبل المسؤول.')
            ->line('إذا كنت تعتقد أن هذا خطأ، يرجى التواصل مع فريق الدعم.')
            ->action('اتصل بالدعم', url('/contact'))
            ->line('شكراً لتفهمك.')
            ->salutation('مع التحية،<br>فريق ' . config('app.name'));

        return $mailMessage;
    }

    /**
     * Get the array representation for the database.
     */
    public function toDatabase(object $notifiable): array
    {
        $data = [
            'title' => 'تم تعطيل حسابك',
            'message' => 'تم تعطيل حسابك في المنصة. لن تتمكن من الوصول إلى خدماتنا حتى يتم فك الحظر.',
            'icon' => 'fas fa-user-slash',
            'url' => url('/contact'),
            'type' => 'account_blocked'
        ];

        if ($this->reason) {
            $data['reason'] = $this->reason;
        }

        return $data;
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        $data = [
            'title' => 'تم تعطيل حسابك',
            'message' => 'تم تعطيل حسابك في المنصة. لن تتمكن من الوصول إلى خدماتنا حتى يتم فك الحظر.',
            'url' => url('/contact'),
            'type' => 'account_blocked'
        ];

        if ($this->reason) {
            $data['reason'] = $this->reason;
        }

        return $data;
    }
}