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
                        ->text('emails.customersupport_to_admin_plain')                        
                        ->to(Config::get('mail.from.address'))
                        //->cc('esi.roy.dev@gmail.com')
                        ->replyTo('bhadz.trex+1235@gmail.com', 'bhadz.trex+1235@gmail.com')
                        ->subject('マイチューター カスタマーサポート')
                        ->attach($this->data['attachment']->getRealPath(),['as' => $this->data['attachment']->getClientOriginalName(),'mime' => $this->data['attachment']->getClientMimeType()]); 
        } else {
            return $this->view('emails.customersupport_to_admin')
                        ->text('emails.customersupport_to_admin_plain')
                        ->to(Config::get('mail.from.address'))
                        //->cc('esi.roy.dev@gmail.com')
                        ->replyTo('bhadz.trex+1235@gmail.com', 'bhadz.trex+1235@gmail.com')
                        ->subject('マイチューター カスタマーサポート');            
        }
    } 
    
}
