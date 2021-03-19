<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LessonScheduleEmailSend extends Mailable
{
    use Queueable, SerializesModels;


    protected $details;    
    protected $member;
    protected $tutor;
    protected $scheduleItem;    
        
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($details, $member, $tutor, $scheduleItem)
    {
        $this->details = $details;        
        $this->member = $member;
        $this->tutor = $tutor;
        $this->scheduleItem = $scheduleItem;           
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $member = $this->member;
        $tutor = $this->tutor;
        $scheduleItem = $this->scheduleItem;

        if ($member && $tutor && $scheduleItem) {
            return $this->view($this->details['template'], compact('member','tutor', 'scheduleItem'), function ($member) {
                $message->getHeaders()->addTextHeader('-f'.$member->user->email);
            })->subject($this->details['subject']);
        }        
    }
}
