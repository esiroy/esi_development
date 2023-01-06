<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberFeedbackDetails extends Model
{
    public $table = 'member_feedback_details';
    
    protected $guarded = array('created_at', 'updated_at'); 
}
