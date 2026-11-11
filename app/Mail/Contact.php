<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class Contact extends Mailable
{
    use Queueable, SerializesModels;

 

    /**
     * Create a new message instance.
     */
    public function __construct(public $name, public $subjectLine, public $email, public $content)
    {
    }

    // public function build() {
    //     return $this->subject($this->subjectLine)->view("emails.newsletter")->with([
    //         "content" => $this->content,
    //         "subjectLine" => $this->subjectLine,
    //     ]);
    // }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subjectLine,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.contact',
            with : [
                "title" => $this->email,
                "content" => $this->content,
                "subjectLine" => $this->subjectLine,
                "name" => $this->name
            ]
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
