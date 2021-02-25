<?php

namespace App\Models;

use App\Models\Member;

use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{

    //public $table = 'agents';

    public $timestamps = false;    

    protected $guarded = array('created_at', 'updated_at');

    public function getAgentInfo($agentID) 
    {
        return Agent::where('user_id', $agentID)->first();
    }


    public function getMemberAgent($memberID) 
    {
        //get agent id from mmember
        $member = Member::where('user_id', $memberID)->first();

        if ($member) {

            $agent = Agent::where('user_id', $member->agent_id)->first();
        
            if (isset($agent)) {
                return $agent;
            } else {
                return null;
            }
            
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