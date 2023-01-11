<?php
/*
    @description: Selected Lesson for Lesson slide 
*/ 
namespace App\Models;
use Illuminate\Database\Eloquent\Model;


class MemberSelectedLessonSlideMaterial extends Model
{
    //public $table = 'member_selected_lesson_material';

    protected $guarded = array('created_at', 'updated_at');


      /**     
     * @desc  determine if the user pre-selected a lesson
     * @param  $userID, $lessonScheduleID
     */
    
    public function selectedLesson($userID, $lessonScheduleID) 
    {      
        $memberSelectedLesson = MemberSelectedLessonSlideMaterial::where('user_id', $userID)->where('schedule_id', $lessonScheduleID)->first();        

        if ($memberSelectedLesson) {

           return $memberSelectedLesson;

        } else {

            return null;
        }
    }


    /**
     * API - create a new selected lesson slides
     *
     * @param  $userID, $lessonScheduleID,$selectedOption
     */
    public function saveSelectedLesson($userID, $lessonScheduleID, $selectedOption)
    {
        $item = MemberSelectedLessonSlideMaterial::where('user_id', $userID )
                                                    ->where('schedule_id', $lessonScheduleID)                                            
                                                    ->first(); 
        if ($item) {
        
            $item->update([
                    'user_id'       => $userID,
                    'schedule_id'   => $lessonScheduleID,
                    'folder_id'     => $selectedOption['id'],
            ]);

        } else {

            MemberSelectedLessonSlideMaterial::create([
                    'user_id'       => $userID,
                    'schedule_id'   => $lessonScheduleID,
                    'folder_id'     => $selectedOption['id'],            
            ]);
        }
    }    
}
