<?php

namespace App\Mail;

use App\Models\Attempts;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendResultEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $attempt;

    /**
     * Create a new message instance.
     */
    public function __construct(Attempts $attempts)
    {
        $this->attempt = $attempts;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Quiz Result',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.result',
            with: [
                'attempt' => $this->attempt,
                'quiz' => $this->attempt->quiz,
                'user' => $this->attempt->user,
            ],
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
