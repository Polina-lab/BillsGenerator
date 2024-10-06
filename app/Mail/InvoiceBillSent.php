<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class InvoiceBillSent extends Mailable
{
    use Queueable, SerializesModels;

    protected $username;
    public $data;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user , $data )
    {
        $this->data = $data;
        $this->username = $user ?? "Info";
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('info@gloreal.ee',$this->username)->view('mails.download', ['data' => $this->data ] );
    }
}
