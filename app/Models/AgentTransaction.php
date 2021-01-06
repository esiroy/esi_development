<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgentTransaction extends Model
{
    public $table = 'agent_transaction';

    protected $guarded = array('created_at', 'updated_at');

    private $limit = 30;    

    public function getCredits($memberID) 
    {
        $transactions = AgentTransaction::where('member_id', $memberID)->where('valid', 1)->orderBy('created_at', 'ASC')->get(); 
        $credits = 0;
        foreach($transactions as $transaction) {
            if ($transaction->transaction_type == 'ADD'||
                $transaction->transaction_type == 'MANUAL_ADD' || 
                $transaction->transaction_type == 'FREE_CREDITS' || 
                $transaction->transaction_type == 'DISTRIBUTE' || 
                $transaction->transaction_type == 'CANCEL_LESSON' ||
                $transaction->transaction_type == 'CREDITS_EXPIRATION' 
                
                )
            {
                $credits = $credits + $transaction->amount;

            } else {
                $credits = $credits - $transaction->amount;
            }

            //echo $transaction->created_at . "  | " . $transaction->amount . "  ". $transaction->transaction_type . "<BR>";
        }  
        
        return $credits;
    }

    //List ALL specific Member Payment History
    public function getPaymentHistory($memberID) 
    {
        $paymentHistory = new AgentTransaction();
        $transactions = AgentTransaction::where('member_id', $memberID)->where('valid', 1)->where(function($q) use ($memberID) 
        {
            $q->orWhere('transaction_type', 'ADD')
                ->orWhere('transaction_type', 'MANUAL_ADD')
                ->orWhere('transaction_type', 'FREE_CREDITS')
                ->orWhere('transaction_type', 'DISTRIBUTE')
                ->orWhere('transaction_type', 'CREDITS_EXPIRATION');

        })->paginate($this->limit);

     
        return $transactions;
    }


}
