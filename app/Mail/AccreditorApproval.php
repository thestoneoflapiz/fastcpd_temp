<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AccreditorApproval extends Mailable
{
    use Queueable, SerializesModels;

    public $data_record;
    public $provider;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data_record, $provider)
    {
        $this->data_record = $data_record;
        $this->provider = $provider;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    { 
        return $this->subject("Accreditor Feedback")
            ->from("noreply@fastcpd.com")
            ->markdown('email.accreditor.approval');
    }
}
