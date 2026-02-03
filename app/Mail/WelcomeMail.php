<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $application;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $application)
    {
        $this->user = $user;
        $this->application = $application;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $user = $this->user;
        $application = $this->application;
        return $this->markdown('emails.welcome', compact('user', 'application'));
    }
}
