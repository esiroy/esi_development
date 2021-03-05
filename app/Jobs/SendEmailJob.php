<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\LessonScheduleEmailSend;
use Mail, App;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $details;
    protected $member;
    protected $tutor;    
    protected $scheduleItem;  
    /**
     * Create a new job instance.
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
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //get the template
        $email = new LessonScheduleEmailSend($this->details, $this->member, $this->tutor, $this->scheduleItem);

        if (App::environment(['local'])) 
        {
            Mail::to($this->details['email'])->send($email);

        } else if (App::environment(['staging'])) {
            
            Mail::to($this->details['email'])->send($email);

        } else {
            
            //Live
            Mail::to($this->details['email'])->send($email);
        }

    }
}
