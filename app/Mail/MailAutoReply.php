<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Config;

class MailAutoReply extends Mailable
{
    use Queueable, SerializesModels;


    public $emailTo;
    public $emailFrom;
    public $emailMessage; 
    public $emailSubject;
    protected $emailTemplate;

        

    /**
     * Create a new Auto Reply job instance.
     * 
     * @var $emailTo - Email Address of the desired recipient
     * @var $emailFrom - Email Adress of the sender from
     * @var $Subject
     * @var $message  
     * @var $template - (Default Template - emails.writing.autoreply)
     *
     * @return void
     */
    public function __construct($emailTo, $emailFrom, $emailSubject, $emailMessage, $emailTemplate)
    {
        $this->emailTo = $emailTo;
        $this->emailFrom = $emailFrom;
        $this->emailSubject = $emailSubject;        
        $this->emailMessage = $emailMessage;
        $this->emailTemplate = $emailTemplate;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view($this->emailTemplate)
                    ->text($this->emailTemplate."_plain")                        
                    ->to($this->emailTo['email'])
                    ->replyTo($this->emailFrom['email'], $this->emailFrom['name'])
                    ->subject($this->emailSubject);        
    }
    
}