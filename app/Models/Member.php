<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
        return \Carbon\Carbon::parse($value)->format('m/d/Y  g:i:s a');
    }

    public function getCreditsExpirationAttribute($value) {
        return \Carbon\Carbon::parse($value)->format('m/d/Y  g:i:s a');
    }

}
