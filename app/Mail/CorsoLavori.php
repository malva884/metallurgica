<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CorsoLavori extends Mailable
{
    use Queueable, SerializesModels;
    public $email;
    public $name;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email,$name)
    {
        $this->email = $email;
        $this->name = $name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if($this->email['type'] == 'prelievi'){
            $name = $this->name;
            $route = $this->email['route'];

            return $this->subject($this->email['title'])
                ->view('/content/apps/workstatus/email/prelievi',compact('name','route'));
        }

    }
}
