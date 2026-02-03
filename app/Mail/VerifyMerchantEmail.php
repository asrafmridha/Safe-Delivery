<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyMerchantEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $merchant, $application;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($merchant, $application)
    {
        $this->merchant = $merchant;
        $this->application  = $application;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $merchant   = $this->merchant;
        $application    = $this->application;
        //return $this->view('view.name');
        return $this->markdown('emails.MerchantEmailVerify', compact('merchant', 'application'))
            ->subject($merchant->company_name." Email Verification Link");
    }
}
