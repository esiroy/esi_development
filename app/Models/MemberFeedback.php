<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberFeedback extends Model
{
    public $table = 'member_feedback';
    
    protected $guarded = array('created_at', 'updated_at'); 
}
