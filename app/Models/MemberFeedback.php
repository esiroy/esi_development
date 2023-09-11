<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberFeedback extends Model
{
    public $table = 'member_feedback';
    
    protected $guarded = array('created_at', 'updated_at'); 


    public function saveFeedback($feedbackData) 
    {    
        return MemberFeedback::create([
            'schedule_id'       => $feedbackData['schedule_id'],
            'member_user_id'    => $feedbackData['member_user_id'],
            'tutor_user_id'     => $feedbackData['tutor_user_id'],
            'feedback'          => $feedbackData['feedback'],
            'is_active'         => true,
        ]);
    }
}
