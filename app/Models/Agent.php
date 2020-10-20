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
    
    
}