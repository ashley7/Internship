<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class Resetpasswordmail extends Mailable
{
    use Queueable, SerializesModels;

     public function __construct(
        public User   $user,
        public string $resetUrl,
        public string $token,
        public string $email,
    ) {}
 
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reset Your MedIntern Password',
        );
    }
 
    public function content(): Content
    {
        return new Content(
            view: 'emails.reset-password',
            with: [
                'user'     => $this->user,
                'resetUrl' => $this->resetUrl,
            ],
        );
    }
}
