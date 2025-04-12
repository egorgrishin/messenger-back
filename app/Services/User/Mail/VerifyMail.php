<?php

namespace App\Services\User\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerifyMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly int $code,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Verify Mail',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.verify',
        );
    }
}
