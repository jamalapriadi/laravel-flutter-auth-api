<?php

namespace App\Mail\Api;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

use Illuminate\Mail\Mailables\Address;

class RegistrasiEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $full_name;
    public $link_activation;
    public $otp;

    public function __construct($full_name, $link_activation, $otp)
    {
        $this->full_name = $full_name;
        $this->link_activation = $link_activation;
        $this->otp = $otp;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Registrasi Account',
            from: new Address(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME')),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.api.registrasi',
            with:[
                'full_name'=>$this->full_name,
                'link_activation'=>$this->link_activation
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
