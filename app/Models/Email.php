<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class Email extends Model
{
    use HasFactory;

    static function send($to=null,$recipients=null,$subject=null,$view=null,$data=null){

        $to_name = 'Gregorio Grande';
        $to_email = 'gregorio.grande@stl.tech';
        $data = array('name'=>"Sam Jose", "body" => "Test mail");
        env('MAIL_FROM_ADDRESS');
        Mail::send('email.demoMail', $data, function($message) use ($to_name, $to_email) {
            $message->to($to_email, $to_name)
                ->subject('Artisans Web Testing Mail');
            $message->from(env('MAIL_FROM_ADDRESS'),'TODO');
        });
    }
}
