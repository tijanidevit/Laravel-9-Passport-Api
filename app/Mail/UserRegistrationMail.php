<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserRegistrationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $stage_name = ''; 
    public $subject = ''; 
    public $verification_code = ''; 

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($stage_name,$subject,$verification_code)
    {
        $this->stage_name = $stage_name;
        $this->subject = $subject;
        $this->verification_code = $verification_code;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->subject)->view('mails.verification');
    }
}
