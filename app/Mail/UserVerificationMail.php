<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserVerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $status;
    public $notes;

    public function __construct(User $user, $status, $notes = null)
    {
        $this->user = $user;
        $this->status = $status;
        $this->notes = $notes;
    }

    public function build()
    {
        $subject = $this->status === 'approved'
            ? 'Account Verification Approved - My Kuku Soko'
            : 'Account Verification Update - My Kuku Soko';

        return $this->subject($subject)
            ->markdown('emails.user-verification')
            ->with([
                'user' => $this->user,
                'status' => $this->status,
                'notes' => $this->notes,
            ]);
    }
}
