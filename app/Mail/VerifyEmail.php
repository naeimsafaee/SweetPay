<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyEmail extends Mailable{
    use Queueable, SerializesModels;

    private $link;

    public function __construct($link){
        $this->link = $link;
    }

    public function build(){
        $link = $this->link;
        return $this->subject("SweetPay verify email requested!")
            ->from('verify@axaval.ir')
            ->view('verify_mail' , compact("link"));
    }
}
