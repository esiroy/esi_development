<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\ScheduleItem;
use App\Models\MemberMultiAccount;
use App\Models\MemberMultiAccountAlias;

class MemberMultiAccountController extends Controller
{

   
    public function listMemberMultiAccount(Request $request,  MemberMultiAccountAlias $memberMultiAccountAlias) 
    {
        $userID = $request->memberID; 
        $MemberAliasAccounts = $memberMultiAccountAlias->getMemberSelectedAccounts($userID);

        if (count($MemberAliasAccounts) >= 1) {

            $results = [                
                'accounts'  => $MemberAliasAccounts,
                'isAliasAccount' => true,
                'success'   => true,               
            ];              
        } else {
         
            $results = [                                
                'isAliasAccount' => false,
                'success'   => true,               
            ];                  
        }


        return $results;
    }


    public function getMemberMultiAccount(Request $request, Member $member, ScheduleItem $scheduleItem, MemberMultiAccount $memberMultiAccount, MemberMultiAccountAlias $memberMultiAccountAlias) 
    {

        $userID = $request->memberID; 

        //get member alias accounts
        $MemberAliasAccounts = $memberMultiAccountAlias->getMemberAliasAccounts($userID);

        //count member active schedules
        $memberInfo = $member->where('user_id', $userID)->first();
        $totalScheduledItem = $scheduleItem->getTotalMemberReserved($memberInfo);


        if (count($MemberAliasAccounts) >= 1) {

            $results = [                
                'accounts'  => $MemberAliasAccounts,
                'totalScheduledItem' =>  $totalScheduledItem,
                'isAliasAccount' => true,
                'success'   => true,               
            ];  

            return $results;

        } else {

            //if no accounts then get the defaults
            $accounts = $memberMultiAccount->getAccounts();

            if (count($accounts) >= 1) {

                //make first accouunt selected            
                foreach($accounts as $key => $account) {
                    if ($key == 0) {
                        $account['selected'] = true;    
                    } else{
                        $account['selected'] = false;
                    }
                    
                }
                $results = [                
                    'accounts'  => $accounts,
                    'totalScheduledItem' =>  $totalScheduledItem,
                    'isAliasAccount' => false,
                    'success'   => true,               
                ];  
                
                return $results;

            } else {
                
                $results = [                
                    'message'   => "Error, No multiple member accounts detected",
                    'totalScheduledItem' =>  $totalScheduledItem,
                    'isAliasAccount' => false,
                    'success'   => false,               
                ];  

                return $results;
            }            

        }

  
    }


    public function saveMemberMultiAccount(Request $request, MemberMultiAccountAlias $memberMultiAccountAlias) 
    {

        $userID = $request->memberID; 
        $accounts = $request->accounts; 
        $isAliasAccount = $request->isAliasAccount;
       
        foreach($accounts as $account) {

            if ( $account['selected'] == true ||  $account['selected'] == 1) {
                $isSelected = true;
            } else {
                $isSelected = false;
            }

            $user = MemberMultiAccountAlias::updateOrCreate(
                [
                    'user_id'           => $userID,
                    'member_multi_account_id' =>( $isAliasAccount == true)?  $account['member_multi_account_id'] : $account['id'],
                 ],
                 [
                    'name' => $account['name'],
                    'selected' =>  $isSelected,
                    'sequence_number'=> $account['sequence_number'],                  
                    'is_default' => $account['is_default'],
                    //'valid' => $account['selected'],
                    'valid' => true,
                 ]    
            );
        }
                
        

        $result = [                
            'message'   => "You have updated your account successfully", 
            'accounts' =>  $request->accounts,
            'success'   => true,               
        ];  

        return $result;
    }

    public function getMultiAccountOptions(Request $request,  MemberMultiAccountAlias $memberMultiAccountAlias) {
        $userID = $request->memberID; 


        $accounts = $memberMultiAccountAlias->select('id','selected','member_multi_account_id', 'name','is_default')->where('user_id', $userID)            
            ->where('valid', true)
            ->where('selected', true)
            ->orderBy('sequence_number', 'ASC')->get();

            return Response()->json([
                "success" => true,
                "accounts" => $accounts
            ]);               
    }

   
}
