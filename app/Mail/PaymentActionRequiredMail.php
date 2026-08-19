<?php

namespace App\Mail;

use App\Models\Agreement;
use App\Models\AgreementLink;
use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentActionRequiredMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Agreement $agreement,
        public Payment $payment,
        public AgreementLink $link,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Action Required for Payment — {$this->agreement->title}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.payment-action-required',
            with: [
                'agreement' => $this->agreement,
                'payment' => $this->payment,
                'link' => $this->link,
            ],
        );
    }
}
