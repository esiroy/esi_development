<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\MemberAttribute;
use App\Models\ScheduleItem;
use Auth;

class Member extends Model
{
    public $table = 'members';

    protected $guarded = array('created_at', 'updated_at');

    public function user() 
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function tutor() 
    {
        return $this->hasOne('App\Models\Tutor', 'id', 'tutor_id');
    }

    public function agent() {
        return $this->hasOne('App\Models\Agent', 'id', 'agent_id');
    }

    public function aliasAccounts()
    {
        return $this->hasMany('App\Models\MemberMultiAccountAlias', 'user_id', 'user_id');       
    }    
    

    public function selectedAliasAccounts()
    {
        return $this->hasMany('App\Models\MemberMultiAccountAlias', 'user_id', 'user_id')
                    ->where('selected', 1)       
                    ->where('valid', 1);
    }    

    public function defaultAliasAccount()
    {
      return $this->hasMany('App\Models\MemberMultiAccountAlias', 'user_id', 'user_id')
                  ->where('is_default', 1);
    }

    public function getLatestPurchaseDateAttribute($value) {
        if (isset($value)) {
            return \Carbon\Carbon::parse($value)->format('m/d/Y  g:i:s a');
        } else {
            return null;
        }
        
    }

    public function getCreditsExpirationAttribute($value) {
        if (isset($value)) {
            return \Carbon\Carbon::parse($value)->format('m/d/Y  g:i:s a');
        } else {
            return null;
        }
    }


    public function getTutorName() {
        $user = Auth::user();
        $memberInfo = Member::where('user_id', $user->id)->first();       
        if (isset($memberInfo->id)) 
        {
            $tutorInfo = Tutor::where('user_id', $memberInfo->tutor_id)->first();

            if (isset($tutorInfo->user->firstname)) {
                return $tutorInfo->user->firstname;            
            } else {
                return null;
            }
            
        } else {
            return null;
        }
    }


    public function getSkype() 
    {
        $user = Auth::user();
        $memberInfo = Member::where('user_id', $user->id)->first();

        if (isset($memberInfo->skype_account)) {
            return $memberInfo->skype_account;
        } else {
            return null;
        }
    }

    public function getZoom() {
        $user = Auth::user();
        $memberInfo = Member::where('user_id', $user->id)->first();       

        if (isset($memberInfo->zoom_account)) {
            return $memberInfo->zoom_account;   
        } else {
            return null;
        }
    }


    
    //returns: current lesson limit
    public function getLessonLimit() 
    {
        $user = Auth::user();
        $memberAttributeObj = new MemberAttribute();
        $memberAttribute = $memberAttributeObj->getCurrentMonthLessonLimit($user->id);

        if ($memberAttribute) {
            return $memberAttribute->lesson_limit;
        } else {
            return null;
        }
        
    }

    public function getLessonConsumed() 
    {
        $user = Auth::user();        
        $scheduleItemObj = new ScheduleItem();        
        return $scheduleItemObj->getTotalReservedForCurrentMonth($user->id);
    }


    public function getMonthlyLessonsLeft() {
        $credits = $this->getLessonLimit();
        $consumed = $this->getLessonConsumed();
        
        $remainingMonthlyCredits = $credits - $consumed;
                
        return $remainingMonthlyCredits;
    }


    /* Member ID */
    public function getMemberMonthlyLessonsLeft($memberID) 
    {
        $credits = $this->getMemberLessonLimit($memberID);
        $consumed = $this->getLessonMemberConsumed($memberID);
        $remainingMonthlyCredits = $credits - $consumed;

        return $remainingMonthlyCredits;
    
    }



    public function getMemberLessonLimit($memberID) 
    {
        
        $memberAttributeObj = new MemberAttribute();
        $memberAttribute = $memberAttributeObj->getCurrentMonthLessonLimit($memberID);

        if ($memberAttribute) {
            return $memberAttribute->lesson_limit;
        } else {
            return null;
        }
        
    }

    public function getLessonMemberConsumed($memberID) {
             
        $scheduleItemObj = new ScheduleItem();        
        return $scheduleItemObj->getTotalReservedForCurrentMonth($memberID);
    }







    public function isMemberCreditExpired($memberID) 
    {
        $today = date("Y-m-d, H:i");

        $member = new Member();
        $memberInfo = $member->where('user_id', $memberID)->first();

        if ($memberInfo) 
        {
            $expiry = date("Y-m-d, 00:30", strtotime($memberInfo->credits_expiration ." + 1 day"));
            if ($today > $expiry) {
                return true;
            } else {
                return false;
            }
        } else {
            return true; //return true, set this as expired so it member can't book
        }
    }

    public function isReservedLessonValid($memberID, $lessonTime) 
    {   
        $memberInfo = Member::where('user_id', $memberID)->first();

        $lessonTime = date("Y-m-d, H:i", strtotime($lessonTime));
        $expiry = date("Y-m-d, 00:30:00", strtotime($memberInfo->credits_expiration ." + 1 day"));

        if ($lessonTime < $expiry) {
            return true;
        } else {
            return false;
        }
    }

    public function getNickname($memberID) {
         $memberInfo = Member::where('user_id', $memberID)->first();
         if (isset($memberInfo->nickname)) {
            return $memberInfo->nickname;
         } else {
            return null;
         }
         
    }

}
