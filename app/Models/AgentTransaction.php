<?php

namespace App\Models;

use App\Models\Shift;
use Auth;
use Illuminate\Database\Eloquent\Model;

class AgentTransaction extends Model
{
    public $table = 'agent_transaction';

    protected $guarded = array('created_at', 'updated_at');

    public function addMemberTransactions($memberTransactionData)
    {
        $shift = Shift::where('value', $memberTransactionData['shiftDuration'])->first();

        if ($memberTransactionData['status'] == 'TUTOR_CANCELLED') {
            $transaction = [
                'member_id' => $memberTransactionData['memberID'],
                'lesson_shift_id' => $shift->id,
                'created_by_id' => Auth::user()->id,
                'transaction_type' => "CANCEL_LESSON",
                'amount' => 1,
                'valid' => true,
            ];

            AgentTransaction::create($transaction);

        } else if ($memberTransactionData['status'] == 'CLIENT_RESERVED_B') {

            $transaction = [
                'member_id' => $memberTransactionData['memberID'],
                'lesson_shift_id' => $shift->id,
                'created_by_id' => Auth::user()->id,
                'transaction_type' => "LESSON",
                'amount' => 1,
                'valid' => true,
            ];

            AgentTransaction::create($transaction);

        } else if ($memberTransactionData['status'] == 'CLIENT_RESERVED') {

            $transaction = [
                'member_id' => $memberTransactionData['memberID'],
                'lesson_shift_id' => $shift->id,
                'created_by_id' => Auth::user()->id,
                'transaction_type' => "LESSON",
                'amount' => 1,
                'valid' => true,
            ];

            AgentTransaction::create($transaction);

        } else {

        }

    }


    //List ALL specific Member Payment History (Not Paginated)
    public function getAllPaymentHistory($memberID)
    {
        $paymentHistory = new AgentTransaction();
        $transactions = AgentTransaction::where('member_id', $memberID)->where('valid', 1)->where(function ($q) use ($memberID) {
            $q->orWhere('transaction_type', 'ADD')
                ->orWhere('transaction_type', 'MANUAL_ADD')
                ->orWhere('transaction_type', 'FREE_CREDITS')
                ->orWhere('transaction_type', 'DISTRIBUTE');
                //->orWhere('transaction_type', 'CREDITS_EXPIRATION');
            })->orderBy('created_at', 'DESC')->get();
        return $transactions;
    }

    //List ALL specific Member Payment History (Paginated)
    public function getPaymentHistory($memberID)
    {
        $paymentHistory = new AgentTransaction();
        $transactions = AgentTransaction::where('member_id', $memberID)->where('valid', 1)->where(function ($q) use ($memberID) {
            $q->orWhere('transaction_type', 'ADD')
                ->orWhere('transaction_type', 'MANUAL_ADD')
                //->orWhere('transaction_type', 'FREE_CREDITS')
                ->orWhere('transaction_type', 'DISTRIBUTE')
                ->orWhere('transaction_type', 'CREDITS_EXPIRATION');

        })->orderBy('created_at', 'DESC')->paginate(Auth::user()->items_per_page);
        return $transactions;
    }

    public function getMemberPurcaseAmount($memberID)
    {
        $transactions = AgentTransaction::where('member_id', $memberID)->where('valid', 1)->where(function ($q) use ($agentID) {
            $q->orWhere('transaction_type', 'ADD')
                ->orWhere('transaction_type', 'MANUAL_ADD')
                ->orWhere('transaction_type', 'FREE_CREDITS')
                ->orWhere('transaction_type', 'DISTRIBUTE')
                ->orWhere('transaction_type', 'CREDITS_EXPIRATION');

        })->get();

        $price = 0;
        foreach ($transactions as $transaction) {
            $price = $price + $transaction->price;
        }

        return $price;
    }

    //Get Credits or Point Balance Member
    public function getCredits($memberID)
    {

        $transactions = AgentTransaction::where('member_id', $memberID)->where('valid', 1)->orderBy('created_at', 'ASC')->get();
        $credits = 0;
        foreach ($transactions as $transaction) {
            if ($transaction->transaction_type == 'ADD' ||
                $transaction->transaction_type == 'MANUAL_ADD' ||
                $transaction->transaction_type == 'FREE_CREDITS' ||
                $transaction->transaction_type == 'DISTRIBUTE' ||
                $transaction->transaction_type == 'CANCEL_LESSON' ||
                $transaction->transaction_type == 'CREDITS_EXPIRATION'

            ) {
                $credits = $credits + $transaction->amount;

            } else {
                $credits = $credits - $transaction->amount;
            }
        }
        return $credits;

    }

    public function getAgentCredits($agentID)
    {
        $transactions = AgentTransaction::where('agent_id', $agentID)->where('valid', 1)->orderBy('created_at', 'ASC')->get();
        $credits = 0;
        foreach ($transactions as $transaction) {
            if ($transaction->transaction_type == 'ADD' ||
                $transaction->transaction_type == 'MANUAL_ADD' ||
                $transaction->transaction_type == 'FREE_CREDITS' ||
                $transaction->transaction_type == 'DISTRIBUTE' ||
                $transaction->transaction_type == 'CANCEL_LESSON' ||
                $transaction->transaction_type == 'CREDITS_EXPIRATION'

            ) {
                $credits = $credits + $transaction->amount;

            } else {
                $credits = $credits - $transaction->amount;
            }
        }
        return $credits;

    }

    public function getMemberTransactions($memberID)
    {
        //$transactions = AgentTransaction::where('member_id', $memberID)->where('valid', 1)->orderBy('created_at', 'DESC')->get();
        
        $transactions = AgentTransaction::where('member_id', $memberID)->where('valid', 1)->orderBy('created_at', 'DESC')->where(function ($q) use ($memberID) {
            $q->orWhere('transaction_type', 'ADD')
                ->orWhere('transaction_type', 'LESSON')
                ->orWhere('transaction_type', 'CANCEL_LESSON')
                ->orWhere('transaction_type', 'MANUAL_ADD')
                ->orWhere('transaction_type', 'FREE_CREDITS')                
                ->orWhere('transaction_type', 'DISTRIBUTE')
                ->orWhere('transaction_type', 'AGENT_SUBTRACT')
                ->orWhere('transaction_type', 'CREDITS_EXPIRATION');
                

        })->get();
       

        return $transactions;
    }

    /*
        1. Distrube transaction is the only type that will change the latest purchase date.
        2. 
    */
    public function getMemberLatestDateOfPurchase($memberID)
    {

        $transaction = AgentTransaction::where('member_id', $memberID)->where('valid', 1)->where(function ($q) use ($memberID) {
            $q->orWhere('transaction_type', 'ADD')
            ->orWhere('transaction_type', 'DISTRIBUTE')
            ->orWhere('transaction_type', 'MANUAL_ADD');
            //->orWhere('transaction_type', 'FREE_CREDITS');            
            //->orWhere('transaction_type', 'CREDITS_EXPIRATION');

        })->orderBy('id', "DESC")->first();

        if (isset($transaction->created_at)) {
            return $transaction->created_at;
        } else {
            return null;
        }
    }




    
    

    public function getAgentTransactions($agentID)
    {
        $transactions = AgentTransaction::where('agent_id', $agentID)->where('valid', 1)->orderBy('created_at', 'DESC')->get();
        return $transactions;
    }




    //List ALL specific AGENT || Point Purchase History (AGENT LISTINGS)
    public function getAgentPaymentHistory($agentID)
    {
        $paymentHistory = new AgentTransaction();
        $transactions = AgentTransaction::where('agent_id', $agentID)->where('valid', 1)->where(function ($q) use ($agentID) {
            $q->orWhere('transaction_type', 'ADD')
                ->orWhere('transaction_type', 'MANUAL_ADD')
                ->orWhere('transaction_type', 'SUBTRACT');
                /*@checked: add, subtract are only viewing in agent transactions */
                /*
                ->orWhere('transaction_type', 'MANUAL_ADD')
                ->orWhere('transaction_type', 'FREE_CREDITS')
                ->orWhere('transaction_type', 'DISTRIBUTE')
                ->orWhere('transaction_type', 'CREDITS_EXPIRATION');
                */

        })->orderby('created_at', 'DESC')->paginate(Auth::user()->items_per_page);

        return $transactions;
    }

    //List ALL specific AGENT || Point Purchase History (AGENT LISTINGS)
    public function getAgentAllPaymentHistory($agentID)
    {
        $paymentHistory = new AgentTransaction();
        $transactions = AgentTransaction::where('agent_id', $agentID)->where('valid', 1)->where(function ($q) use ($agentID) {
            $q->orWhere('transaction_type', 'ADD')
                ->orWhere('transaction_type', 'MANUAL_ADD')
                ->orWhere('transaction_type', 'SUBTRACT');
                /*@checked: add, subtract are only viewing in agent transactions */
                /*
                ->orWhere('transaction_type', 'MANUAL_ADD')
                ->orWhere('transaction_type', 'FREE_CREDITS')
                ->orWhere('transaction_type', 'DISTRIBUTE')
                ->orWhere('transaction_type', 'CREDITS_EXPIRATION');
                */

        })->orderby('created_at', 'DESC')->get();

        return $transactions;
    }    
        
    public function getAgentPurchasedAmount($agentID)
    {
        $transactions = AgentTransaction::where('agent_id', $agentID)->where('valid', 1)->where(function ($q) use ($agentID) {
            $q->orWhere('transaction_type', 'ADD')
                ->orWhere('transaction_type', 'MANUAL_ADD')
                ->orWhere('transaction_type', 'SUBTRACT');
                /*@checked: add, subtract are only viewing in agent transactions */
                /*
                ->orWhere('transaction_type', 'FREE_CREDITS')
                ->orWhere('transaction_type', 'DISTRIBUTE')
                ->orWhere('transaction_type', 'CREDITS_EXPIRATION');
                */
        })->get();

        $price = 0;
        foreach ($transactions as $transaction) {
            if ($transaction->transaction_type === "SUBTRACT") {
                $price = $price - $transaction->price;
            } else {
                $price = $price + $transaction->price;
            }
        }

        return $price;
    }

    public function getAgentFirstDateOfPurchase($agentID)
    {
        $transaction = AgentTransaction::where('agent_id', $agentID)->where('valid', 1)->where('transaction_type', 'ADD')
            ->orWhere('transaction_type', 'MANUAL_ADD')
            ->orWhere('transaction_type', 'FREE_CREDITS')
            ->orWhere('transaction_type', 'DISTRIBUTE')
            ->orWhere('transaction_type', 'CREDITS_EXPIRATION')->orderBy('id', 'ASC')->first();

        if (isset($transaction->created_at)) {
            return $transaction->created_at;
        } else {
            return null;
        }
    }

    public function getAgentLatestDateOfPurchase($agentID)
    {

        $transaction = AgentTransaction::where('agent_id', $agentID)->where(function ($q) use ($agentID) {
            $q->orWhere('transaction_type', 'ADD')
                ->orWhere('transaction_type', 'MANUAL_ADD')
                ->orWhere('transaction_type', 'FREE_CREDITS')
                ->orWhere('transaction_type', 'DISTRIBUTE')
                ->orWhere('transaction_type', 'CREDITS_EXPIRATION');

        })->orderBy('id', "DESC")->where('valid', 1)->first();

        if (isset($transaction->created_at)) {
            return $transaction->created_at;
        } else {
            return null;
        }
    }

}
