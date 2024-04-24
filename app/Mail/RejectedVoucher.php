<?php

namespace App\Mail;

use App\{ User, Voucher };
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RejectedVoucher extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $voucher;
    public $message;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $voucher, $message)
    {
        $this->user = $user;
        $this->voucher = $voucher;
        $this->message = $message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Voucher Rejected!")
            ->from("noreply@fastcpd.com")
            ->markdown('email.verifications.reject_voucher');
    }
}
