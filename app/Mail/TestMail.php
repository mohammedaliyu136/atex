<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $system_settings;
    public $type;

    /**
     * Create a new message instance.
     */
    public function __construct($type = 'general')
    {
        $this->system_settings = \App\Models\Setting::getAllSettings();
        $this->type = $type;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $prefix = $this->type === 'kyc' ? 'KYC SMTP Test' : 'System SMTP Test';
        return new Envelope(
            subject: $prefix . ' - ' . ($this->system_settings['platform_name'] ?? 'ATEX'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.test-mail',
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
