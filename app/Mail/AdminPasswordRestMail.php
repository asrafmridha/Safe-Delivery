<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminPasswordRestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $application;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data, $application)
    {
        $this->data = $data;
        $this->application = $application;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $data           = $this->data;
        $application    = $this->application;

        return $this->markdown('emails.AdminPasswordReset', compact('data', 'application'))
        ->subject($data['admin_name']." Reset Password Link");
    }
}
