<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $subject;
    public $message;

    public function __construct(User $user, $subject, $message)
    {
        $this->user = $user;
        $this->subject = $subject;
        $this->message = $message;
    }

    public function build()
    {
        return $this->subject($this->subject)
            ->markdown('emails.admin-notification')
            ->with([
                'user' => $this->user,
                'content' => $this->message,
            ]);
    }
}
