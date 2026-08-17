<?php

namespace App\Mail;

use App\Models\GetServiceRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class GetServiceStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public GetServiceRequest $request,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Service Request Update — #GS-'.str_pad($this->request->id, 6, '0', STR_PAD_LEFT),
        );
    }

    public function content(): Content
    {
        $request = $this->request;

        $agreement = new \stdClass;
        $agreement->agreement_number = 'GS-'.str_pad($request->id, 6, '0', STR_PAD_LEFT);
        $agreement->client_name = $request->full_name;
        $agreement->client_email = $request->email;
        $agreement->created_at = $request->created_at;
        $agreement->signed_at = null;
        $agreement->title = 'Service Request';

        return new Content(
            view: 'mail.get-service',
            with: [
                'agreement' => $agreement,
            ],
        );
    }
}
