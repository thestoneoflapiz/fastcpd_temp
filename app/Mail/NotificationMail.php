<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $notif_data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($notif_data)
    {
        $this->notif_data = $notif_data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->notif_data['subject'])
            ->from("noreply@fastcpd.com")
            ->markdown('email.notification.notification');
    }
}
