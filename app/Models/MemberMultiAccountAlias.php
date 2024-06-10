<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\ScheduleItem ;

class MemberMultiAccountAlias extends Model
{
    public $table = 'member_multi_account_alias';

    protected $guarded = array();


    public function getMemberDefaultAccount($userID) {
        return $this->where('user_id', $userID)
                ->where('valid', true)
                ->where('is_default', true)
                ->first();
    }
    
    public function getMemberAccountInfo($userID, $member_multi_account_id) {
        return $this->where('user_id', $userID)
                ->where('valid', true)
                ->where('member_multi_account_id', $member_multi_account_id)                
                ->first();
    }
        
    
    //all alias accounts
    public function getMemberAliasAccounts($userID) {

        $scheduleItem = new ScheduleItem();

        
        /*
        return $this->where('user_id', $userID)
                    ->where('valid', true)
                    ->orderBy('sequence_number', 'ASC')->get();
        */
        $accounts = $this->where('user_id', $userID)
                    ->where('valid', true)
                    ->orderBy('sequence_number', 'ASC')->get();

        $accountArray = null;

        
        foreach ($accounts as $key => $account) {
            $accountArray[$key] = $account;          
            $accountArray[$key]['scheduledItemCount'] = $scheduleItem->getTotalMemberMultiAccountReserved($account->user_id, $account->member_multi_account_id);
        }

        return $accountArray;
    }

    //only selected accounts will appear (for dropdowns)
    public function getMemberSelectedAccounts($userID) {
        return $this->where('user_id', $userID)
            ->where('selected', true)
            ->where('valid', true)
            ->orderBy('sequence_number', 'ASC')->get();
    }

    public function getAlias($userID, $member_multi_account_id) {
        $info =  $this->where('user_id', $userID)
                ->where('member_multi_account_id', $member_multi_account_id)                
                ->first();                 
        if ($info) {
            return $info->name;       
        } else{
            return null;
        }
    }

    public function isActivated($userID) {
        return $this->where('user_id', $userID)
        ->where('selected', true)
        ->where('valid', true)
        ->count() >= 1;
    }
}
