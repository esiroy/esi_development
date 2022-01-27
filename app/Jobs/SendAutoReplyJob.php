<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\MailAutoReply;
use Mail, App;

class SendAutoReplyJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public $emailTo;
    public $emailFrom;
    public $emailMessage; 
    public $emailSubject;
    public $attachment;
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
    public function __construct($emailTo, $emailFrom, $emailSubject, $emailMessage, $emailTemplate, $attachment = null, $showlabel = false)
    {
        $this->emailTo = $emailTo;
        $this->emailFrom = $emailFrom;
        $this->emailSubject = $emailSubject;        
        $this->emailMessage = $emailMessage;
        $this->emailTemplate = $emailTemplate;
        $this->attachment = $attachment;
        $this->showLabel = $showlabel;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        
        $emailTo = $this->emailTo;
        $emailFrom = $this->emailFrom;
        $message = $this->emailMessage;
        $emailSubject = $this->emailSubject;
        $emailTemplate = $this->emailTemplate;
        $showlabel = $this->showLabel;

        if (isset($this->attachment)) {
            $emailAttachment = $this->attachment;
        } else {
            $emailAttachment = null;
        }
        
       


        //$email = new MailAutoReply($emailTo, $emailFrom, $emailSubject, $emailMessage, $emailTemplate)
        //Mail::to($emailTo)->send($email);

        Mail::send(new MailAutoReply($emailTo, $emailFrom, $emailSubject, $message, $emailTemplate, $emailAttachment, $showLabel));
    }
}
