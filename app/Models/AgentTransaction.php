<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgentTransaction extends Model
{
    public $table = 'agent_transaction';

    protected $guarded = array('created_at', 'updated_at');

    private $limit = 50;

    //List ALL specific AGENT Payment History
    public function getAgentPaymentHistory($agentID)
    {
        $paymentHistory = new AgentTransaction();
        $transactions = AgentTransaction::where('agent_id', $agentID)->where(function ($q) use ($agentID) {
            $q->orWhere('transaction_type', 'ADD')
                ->orWhere('transaction_type', 'MANUAL_ADD')
                ->orWhere('transaction_type', 'FREE_CREDITS')
                ->orWhere('transaction_type', 'DISTRIBUTE')
                ->orWhere('transaction_type', 'CREDITS_EXPIRATION');

        })->paginate($this->limit);

        return $transactions;
    }

    //List ALL specific Member Payment History (Paginated)
    public function getPaymentHistory($memberID)
    {
        $paymentHistory = new AgentTransaction();
        $transactions = AgentTransaction::where('member_id', $memberID)->where(function ($q) use ($memberID) {
            $q->orWhere('transaction_type', 'ADD')
                ->orWhere('transaction_type', 'MANUAL_ADD')
                ->orWhere('transaction_type', 'FREE_CREDITS')
                ->orWhere('transaction_type', 'DISTRIBUTE')
                ->orWhere('transaction_type', 'CREDITS_EXPIRATION');

        })->orderBy('created_at', 'DESC')->paginate($this->limit);
        return $transactions;
    }

    //List ALL specific Member Payment History (Not Paginated)
    public function getAllPaymentHistory($memberID)
    {
        $paymentHistory = new AgentTransaction();
        $transactions = AgentTransaction::where('member_id', $memberID)->where(function ($q) use ($memberID) {
            $q->orWhere('transaction_type', 'ADD')
                ->orWhere('transaction_type', 'MANUAL_ADD')
                ->orWhere('transaction_type', 'FREE_CREDITS')
                ->orWhere('transaction_type', 'DISTRIBUTE')
                ->orWhere('transaction_type', 'CREDITS_EXPIRATION');

        })->orderBy('created_at', 'DESC')->get();
        return $transactions;
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
        $transactions = AgentTransaction::where('member_id', $memberID)->orderBy('created_at', 'DESC')->get();
        return $transactions;
    }

    public function getAgentTransactions($agentID)
    {
        $transactions = AgentTransaction::where('agent_id', $agentID)->orderBy('created_at', 'DESC')->get();
        return $transactions;
    }

    public function getAgentPurcaseAmount($agentID)
    {

        $transactions = AgentTransaction::where('agent_id', $agentID)->where(function ($q) use ($agentID) {
            $q->orWhere('transaction_type', 'ADD')
                ->orWhere('transaction_type', 'MANUAL_ADD')
                ->orWhere('transaction_type', 'FREE_CREDITS')
                ->orWhere('transaction_type', 'DISTRIBUTE')
                ->orWhere('transaction_type', 'CREDITS_EXPIRATION');

        })->paginate($this->limit);

        $price = 0;
        foreach ($transactions as $transaction) {
            $price = $price + $transaction->price;
        }

        return $price;
    }

    public function getMemberPurcaseAmount($memberID)
    {
        $transactions = AgentTransaction::where('member_id', $memberID)->where(function ($q) use ($agentID) {
            $q->orWhere('transaction_type', 'ADD')
                ->orWhere('transaction_type', 'MANUAL_ADD')
                ->orWhere('transaction_type', 'FREE_CREDITS')
                ->orWhere('transaction_type', 'DISTRIBUTE')
                ->orWhere('transaction_type', 'CREDITS_EXPIRATION');

        })->paginate($this->limit);

        $price = 0;
        foreach ($transactions as $transaction) {
            $price = $price + $transaction->price;
        }

        return $price;
    }

    public function getMemberLatestDateOfPurchase($memberID)
    {

        $transaction = AgentTransaction::where('member_id', $memberID)->where(function ($q) use ($memberID) {
            $q->orWhere('transaction_type', 'ADD')
                ->orWhere('transaction_type', 'MANUAL_ADD')
                ->orWhere('transaction_type', 'FREE_CREDITS')
                ->orWhere('transaction_type', 'DISTRIBUTE')
                ->orWhere('transaction_type', 'CREDITS_EXPIRATION');

        })->orderBy('id', "DESC")->first();

        if (isset($transaction->created_at)) {
            return $transaction->created_at;
        } else {
            return null;
        }
    }

    public function getAgentFirstDateOfPurchase($agentID)
    {
        $transaction = AgentTransaction::where('agent_id', $agentID)->where('transaction_type', 'ADD')
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

        })->orderBy('id', "DESC")->first();

        if (isset($transaction->created_at)) {
            return $transaction->created_at;
        } else {
            return null;
        }
    }



}
