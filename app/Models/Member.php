<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    public $table = 'members';

    protected $guarded = array('created_at', 'updated_at');

    public function user() 
    {
        return $this->hasOne('App\Models\User');
    }
}
