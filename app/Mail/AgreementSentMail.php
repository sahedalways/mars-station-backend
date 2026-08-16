<?php

namespace App\Mail;

use App\Models\Agreement;
use App\Models\AgreementLink;
use App\Models\AgreementVersion;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AgreementSentMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Agreement $agreement,
        public AgreementLink $link,
        public AgreementVersion $version,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Agreement {$this->agreement->agreement_number} — {$this->agreement->title}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.agreement-sent',
            with: [
                'agreement' => $this->agreement,
                'link' => $this->link,
                'version' => $this->version,
            ],
        );
    }
}
