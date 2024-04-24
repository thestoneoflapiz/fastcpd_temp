<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProviderNonUserInvitation extends Mailable
{
    use Queueable, SerializesModels;

    public $provider;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($provider)
    {
        $this->provider = $provider;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("FastCPD SignUp Invitation")
            ->from("noreply@fastcpd.com")
            ->markdown('email.provider.nonuser-invitation');
    }
}
