<?php

namespace App\Mail;

use App\{ User, Voucher };
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApprovedVoucher extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $voucher;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $voucher)
    {
        $this->user = $user;
        $this->voucher = $voucher;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Voucher Approved!")
            ->from("noreply@fastcpd.com")
            ->markdown('email.verifications.approve_voucher');
    }
}
