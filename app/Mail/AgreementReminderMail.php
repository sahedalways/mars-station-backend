<?php

namespace App\Mail;

use App\Models\Agreement;
use App\Models\AgreementLink;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AgreementReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Agreement $agreement,
        public AgreementLink $link,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Reminder: {$this->agreement->title} — please review your agreement",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.agreement-reminder',
            with: [
                'agreement' => $this->agreement,
                'link' => $this->link,
            ],
        );
    }
}
