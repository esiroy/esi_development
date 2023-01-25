<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Auth, DB;

use Carbon\Carbon;


class LessonHistory extends Model
{
    public $table = 'lesson_history';

    public $timestamps = false;

    protected $guarded = array('created_at', 'updated_at');


    /**    
        @description:   - CHECK IF THERE IS A 'NEW' LESSON STATUS CREATED 
                        - UPDATE THE NEW STATUS TO SKIP AND 
                       
        
        SKIP The current lesson and create new lesson 
    */
    public function skipLesson($userID, $lessonScheduleID, $newFolderID) 
    {

        try {
        
            DB::beginTransaction();     

            $lessonHistory = LessonHistory::where('member_id', $userID)->where('schedule_id', $lessonScheduleID)->where('status', "NEW")->first();

            if ($lessonHistory) {


                $newLessonHistory = $lessonHistory->replicate();

                $newLessonHistory->folder_id       = $newFolderID;
                $newLessonHistory->status           = "NEW";
                $newLessonHistory->additional_notes = "replicated from a skipped lesson $lessonHistory->folder_id folder (" . $lessonHistory->folder_id  .")";
               

                $newLessonHistory->save();


                $updated = $lessonHistory->update([
                    'status'            => "SKIPPED",
                   // 'additional_notes'  => "replaced a folder from (" . $lessonHistory->folder_id .") to new (". $newFolderID .")"
                ]);

 

                DB::commit();

                return true;

            } else {
            
                return false;
            }           
        
        } catch (Throwable $e) {

            return false;

            DB::rollBack();
        }
    }


    public function createNewHistoryFromSkippedLesson($userID, $lessonScheduleID, $newFolderID, $lessonHistory) {
    
            LessonHistory::create([
                'schedule_id'       => $lessonScheduleID,
                'member_id'         => $userID,
                'tutor_id'          => $lessonHistory->tutor_id,
                'folder_id'         => $newFolderID,
                'total_slides'      => $lessonHistory->total_slides,
                'current_slide'     => $lessonHistory->current_slide,
                'status'            => "NEW",
                'additional_notes'  => "created new folder " . $newFolderID,
                
                'time_started'      => $lessonHistory->time_started,
                //time_ended
            ]);


    }


}
