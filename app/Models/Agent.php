<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{

    //public $table = 'agents';

    protected $guarded = array('created_at', 'updated_at');


    public function setBirthdateAttribute($value)
    {       
        $this->attributes['birthdate'] = date('Y-m-d', strtotime($value)); 
    }


    public function user() 
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    
}