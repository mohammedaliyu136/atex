<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class KycExporterRejectedNotification extends Notification
{
    use Queueable;

    public User $user;
    public ?string $reason;

    public function __construct(User $user, ?string $reason = null)
    {
        $this->user = $user;
        $this->reason = $reason;
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->mailer('smtp_kyc')
            ->from('kyc@atex.adamawastate.gov.ng', 'Adamawa Ecommerce platform KYC')
            ->subject('Your Exporter Verification Needs Attention')
            ->view('emails.kyc-exporter-rejected', [
                'user' => $this->user,
                'reason' => $this->reason
            ]);
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => 'Exporter Verification Needs Attention',
            'message' => 'Your exporter profile verification requires attention. Please review and resubmit.',
            'profile_type' => 'export',
            'reason' => $this->reason,
        ];
    }
}
