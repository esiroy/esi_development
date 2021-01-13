<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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


    public function getSkype() {
        $user = Auth::user();
        $memberInfo = Member::where('user_id', $user->id)->first();

        if (isset($tutorInfo->skype_account)) {
            return $memberInfo->skype_account;
        } else {
            return null;
        }
    }

    public function getZoom() {
        $user = Auth::user();
        $memberInfo = Member::where('user_id', $user->id)->first();       

        if (isset($tutorInfo->skype_account)) {
            return $memberInfo->skype_account;   
        } else {
            return null;
        }
    }
}
