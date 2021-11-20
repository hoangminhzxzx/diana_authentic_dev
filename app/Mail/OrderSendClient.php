<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderSendClient extends Mailable
{
    use Queueable, SerializesModels;
    public $details_send_mail;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($details_send_mail)
    {
        $this->details_send_mail = $details_send_mail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
//        return $this->view('view.name');
        return $this->subject('Mail from Diana Authentic')
            ->view('emails.order_send_client');
    }
}
