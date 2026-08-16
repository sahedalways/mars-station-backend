<?php

namespace App\Mail;

use App\Models\Agreement;
use App\Models\AgreementLink;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class AgreementOtpMail extends Mailable
{
    use Queueable;

    public function __construct(
        public Agreement $agreement,
        public AgreementLink $link,
        public string $otp,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your verification code for '.$this->agreement->agreement_number,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.agreement-otp',
            with: [
                'agreement' => $this->agreement,
                'link' => $this->link,
                'otp' => $this->otp,
            ],
        );
    }
}
