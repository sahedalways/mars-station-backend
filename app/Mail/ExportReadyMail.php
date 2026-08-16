<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class ExportReadyMail extends Mailable
{
    use Queueable;

    public function __construct(public string $filePath) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your payment export is ready',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.export-ready',
            with: [
                'filePath' => $this->filePath,
            ],
        );
    }
}
