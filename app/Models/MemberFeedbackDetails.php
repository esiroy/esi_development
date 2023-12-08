<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberFeedbackDetails extends Model
{
    public $table = 'member_feedback_details';
    
    protected $guarded = array('created_at', 'updated_at'); 


    public function saveMemberFeedbackDetails($feedbackID, $feedbackDetailData) 
    {
        $feedbackDetails = MemberFeedbackDetails::create([
            'member_feedback_id'    => $feedbackID,
            'name'                  => $feedbackDetailData['name'],
            'description'           => $feedbackDetailData['description'],
            'value'                 => $feedbackDetailData['value'],
            'order_id'              => $feedbackDetailData['order_id'],
            'is_active'             => $feedbackDetailData['is_active'],
        ]);    
    }
}
