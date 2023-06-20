<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\SatisfactionSurveyDetails;
use App\Models\ScheduleItem;

class SatisfactionSurvey extends Model
{
    public $table = 'lesson_survey';
    
    protected $guarded = array('created_at', 'updated_at');   


    function setRating($scheduleID, $rating) {

         $survey = SatisfactionSurvey::where('schedule_id', $scheduleID)->first();

         if (!$survey) {

            $scheduleItem = new ScheduleItem();

            $schedule = ScheduleItem::find($scheduleID);

            $created = SatisfactionSurvey::create([
                'member_user_id'=> $schedule->member_id,
                'tutor_user_id' => $schedule->tutor_id,
                'schedule_id'   => $schedule->id,
                'is_active'     => true,
            ]);

            $surveyDetails = SatisfactionSurveyDetails::where('lesson_survey_id', $created->id)->first();

            if (!$surveyDetails) {
            
                $createdDetails = SatisfactionSurveyDetails::create([
                    'name' => 'teacher_performace_rating',
                    'value' => $rating,
                    'lesson_survey_id' => $created->id,
                    'order_id' => 3,
                    'is_active'     => true,     
                    'description'   => "Teacher Performace Rating"
                ]);
            }

            return [
                'success'           => true,
                'created'           => $created,
                'createdDetails'    => $createdDetails,
                "message"           => "Added Successfully"
            ];
         } else {
            return [
                'success'   => false,
                "message"   => "Survey already added"
            ];
         
         }
    }

   
}
