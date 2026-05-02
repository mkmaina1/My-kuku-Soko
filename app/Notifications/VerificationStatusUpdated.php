<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\VerificationRequest;

class VerificationStatusUpdated extends Notification
{
    use Queueable;

    public $verificationRequest;
    public $status;
    public $adminNotes;

    /**
     * Create a new notification instance.
     */
    public function __construct(VerificationRequest $verificationRequest, $status, $adminNotes = null)
    {
        $this->verificationRequest = $verificationRequest;
        $this->status = $status;
        $this->adminNotes = $adminNotes;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast']; // Remove 'mail' if you don't want email
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
                    ->subject('Verification Request ' . ucfirst($this->status));

        if ($this->status === 'approved') {
            $mail->line('Congratulations! Your verification request has been approved.')
                 ->line('Your account is now verified.')
                 ->action('Go to Profile', url('/profile'));
        } elseif ($this->status === 'rejected') {
            $mail->line('Your verification request has been reviewed.')
                 ->line('Status: Rejected');

            if ($this->adminNotes) {
                $mail->line('Admin Notes: ' . $this->adminNotes);
            }

            $mail->line('You can reapply with corrected documents.')
                 ->action('Reapply Now', url('/profile#verification'));
        } else {
            $mail->line('Your verification request status has been updated to: ' . ucfirst($this->status));
        }

        return $mail;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $statusMessages = [
            'pending' => 'Your verification request has been submitted and is under review.',
            'approved' => 'Congratulations! Your verification request has been approved.',
            'rejected' => 'Your verification request has been rejected.',
        ];

        return [
            'type' => 'verification_status',
            'title' => 'Verification Request ' . ucfirst($this->status),
            'message' => $statusMessages[$this->status] ?? 'Your verification status has been updated.',
            'status' => $this->status,
            'verification_id' => $this->verificationRequest->id,
            'admin_notes' => $this->adminNotes,
            'created_at' => now()->toDateTimeString(),
            'link' => '/profile#verification',
            'icon' => $this->getStatusIcon(),
            'color' => $this->getStatusColor()
        ];
    }

    private function getStatusIcon(): string
    {
        return match($this->status) {
            'approved' => 'fas fa-check-circle',
            'rejected' => 'fas fa-times-circle',
            default => 'fas fa-clock',
        };
    }

    private function getStatusColor(): string
    {
        return match($this->status) {
            'approved' => 'success',
            'rejected' => 'danger',
            default => 'warning',
        };
    }
}
