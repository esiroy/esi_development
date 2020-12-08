<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Manager extends Model
{
    
    public $table = 'managers';

    protected $guarded = array('created_at', 'updated_at');

    public function user() 
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }


    
}
