<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DepositWasSuccessful extends Mailable
{
    use Queueable, SerializesModels;

    public $deposit;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($deposit)
    {
        $this->deposit=$deposit;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mail.deposit');
    }
}
