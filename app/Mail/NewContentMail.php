<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewContentMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public string $type;
    public string $title;
    public string $url;
    public string $body;

    public function __construct(string $type, string $title, string $url)
    {
        $this->type  = ucfirst($type);
        $this->title = $title;
        $this->url   = $url;
        $this->body  = "A new {$this->type} has been added: {$this->title}";
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "New {$this->type} Added: {$this->title}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.new-content',
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
