<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProviderInstructorInvitation extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $provider;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $provider)
    {
        $this->user = $user;
        $this->provider = $provider;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Provider Instructor Invitation")
            ->from("noreply@fastcpd.com")
            ->markdown('email.provider.inst-invitation');
    }
}
