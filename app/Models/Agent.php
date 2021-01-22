<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{

    //public $table = 'agents';

    public $timestamps = false;    

    protected $guarded = array('created_at', 'updated_at');

    public function getMemberAgent($agentID) 
    {
        //get agent id from mmember
        $agent = Agent::join('users', 'users.id', '=', 'agents.user_id')
                ->select('agents.*', 'users.firstname', 'users.lastname')
                ->where('user_id', $agentID)->first();
        
        if (isset($agent)) {
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