<?php

namespace App\Mail\Auth;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AccountStatusEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $status;
    public $reason;
    public $appName;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, string $status, string $reason = null)
    {
        $this->user = $user;
        $this->status = $status;
        $this->reason = $reason;
        $this->appName = config('app.name');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->status === 'blocked' 
            ? 'تغيير في حالة حسابك - ' . $this->appName
            : 'تم تفعيل حسابك - ' . $this->appName;

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $view = $this->status === 'blocked' 
            ? 'emails.account-blocked'
            : 'emails.account-unblocked';

        return new Content(
            view: $view,
            with: [
                'user' => $this->user,
                'status' => $this->status,
                'reason' => $this->reason,
                'appName' => $this->appName,
                'contactUrl' => url('/contact'),
                'loginUrl' => url('/login'),
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}