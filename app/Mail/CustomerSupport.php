<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


use App\Models\Member;
use App\Models\Company;
use Config;

class CustomerSupport extends Mailable
{
    use Queueable, SerializesModels;


    public $member;
    
    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Member $member, $data)
    {
        $this->member = $member;

        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if (isset($this->data['attachment'])) {
            return $this->view('emails.customersupport_to_admin')
            ->to(Config::get('mail.from.address'))
            ->subject('Customer Support Request from ' . $this->data['nickname'])
            ->attach($this->data['attachment']->getRealPath(),
            [
                'as' => $this->data['attachment']->getClientOriginalName(),
                'mime' => $this->data['attachment']->getClientMimeType(),
            ]); 
        } else {
            return $this->view('emails.customersupport_to_admin')
            ->to(Config::get('mail.from.address'))
            ->subject('Customer Support Request from ' . $this->data['nickname'])
        }

    }
}
