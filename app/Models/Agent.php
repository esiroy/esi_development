<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{

    //public $table = 'agents';

    public $timestamps = false;    

    protected $guarded = array('created_at', 'updated_at');

    public function getMemberAgent($memberID) 
    {
        //get agent id from mmember
        $member = Member::where('user_id', $memberID)->first();
        
        if (isset($member)) {
            $agent = User::find( $member->agent_id);
            return $agent;
        } else {
            return null;
        }
    }


    public function setBirthdateAttribute($value)
    {       
        $this->attributes['birthdate'] = date('Y-m-d', strtotime($value)); 
    }


    public function user() 
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    
}