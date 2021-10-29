<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class Document extends Mailable
{
    use Queueable, SerializesModels;

    public $email;
    public $name;
    public $final;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email, $name,$final=null)
    {
        $this->email = $email;
        $this->name = $name;
        $this->final = $final;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $name = $this->name;
        $route = $this->email['route'];
        $final = $this->final;

        return $this->subject($this->email['title'])
            ->view('/content/apps/document/email/notify', compact('name', 'route','final'));

    }
}
