<?php

namespace App\Mail\Api;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

class ForgotPassword extends Mailable
{
    use Queueable, SerializesModels;

    public $full_name;
    public $link_activation;
    public $password;

    public function __construct($full_name, $link_activation, $password)
    {
        $this->full_name = $full_name;
        $this->link_activation = $link_activation;
        $this->password = $password;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Forgot Password Mail',
            from: new Address(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME')),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.api.forgot_password',
            with:[
                'full_name'=>$this->full_name,
                'link_activation'=>$this->link_activation,
                'password'=>$this->password
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
