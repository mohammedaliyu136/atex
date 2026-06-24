<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class KycExporterApprovedNotification extends Notification
{
    use Queueable;

    public User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
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
            ->subject('Your Exporter Verification is Complete')
            ->view('emails.kyc-exporter-approved', [
                'user' => $this->user
            ]);
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => 'Exporter Verification Approved',
            'message' => 'Your exporter profile verification is complete. You can now access export features.',
            'profile_type' => 'export',
        ];
    }
}
