<?php

namespace App\Mail;

use App\Models\Agreement;
use App\Models\AgreementLink;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AgreementCompletedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Agreement $agreement,
        public AgreementLink $link,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Agreement Completed — {$this->agreement->title}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.agreement-completed',
            with: [
                'agreement' => $this->agreement,
                'link' => $this->link,
            ],
        );
    }
}
