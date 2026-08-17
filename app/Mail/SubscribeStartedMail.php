<?php

namespace App\Mail;

use App\Models\AgreementLink;
use App\Models\AgreementSubscription;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubscribeStartedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public AgreementSubscription $subscription,
        public AgreementLink $link,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Subscription Started — {$this->subscription->title}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.subscribe-started',
            with: [
                'subscription' => $this->subscription,
                'link' => $this->link,
            ],
        );
    }
}
