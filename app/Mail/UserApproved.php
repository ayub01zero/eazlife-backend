<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class UserApproved extends Mailable
{
    use Queueable, SerializesModels;
    public $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $token = app('auth.password.broker')->createToken($this->user);

        $url = URL::route('password.reset', [
            'token' => $token,
            'email' => $this->user->getEmailForPasswordReset(),
        ]);

        return $this->subject('Your account has been created!')
            ->view('emails.user-approved')
            ->with([
                'user' => $this->user,
                'link' => $url,
            ]);
    }
}
