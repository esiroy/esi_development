<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberPointPurchaseHistory extends Model
{
    public $table = 'member_point_purchase_history';

    protected $guarded = array('created_at', 'updated_at');

    public function user() 
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function getGetCreatedAtAttribute($value) {
        return \Carbon\Carbon::parse($value)->format('M d Y  g:i a');
    }

    public function getGetUpdatedAtAttribute($value) {
        return \Carbon\Carbon::parse($value)->format('M d Y  g:i a');
    }

}
