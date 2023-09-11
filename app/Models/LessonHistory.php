<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Auth, DB;
use App\Models\Folder;
use App\Models\File;
use App\Models\CustomTutorLessonMaterials;
use App\Models\ScheduleItem;
use App\Models\Tutor;
use Carbon\Carbon;
use App\Models\User;

class LessonHistory extends Model
{
    public $table = 'lesson_history';

    public $timestamps = false;

    protected $guarded = array('created_at', 'updated_at');


    /**
    * Skip a lesson for a user and create a new lesson history with a new folder.
    *
    * @param int $userID The ID of the user.
    * @param int $lessonScheduleID The ID of the lesson schedule.
    * @param int $newFolderID The ID of the new folder.
    * @return bool Whether the lesson was successfully skipped or not.
    */
    public function skipLesson($userID, $lessonScheduleID, $newFolderID) 
    {

        try {
        
            DB::beginTransaction();     

            $lessonHistory = LessonHistory::where('member_id', $userID)->where('schedule_id', $lessonScheduleID)->where('status', "NEW")->first();

            if ($lessonHistory) {               

                $newLessonHistory = $lessonHistory->replicate();

                //@get total slide for the new newFolderID
                $file           = new File();
                $filesCounter   = $file->where('folder_id', $newFolderID)->count();


                //@todo: get the custom upload for this lesson and add to total slides
                $custocustomFiles      = new CustomTutorLessonMaterials();
                $customFilesCounter    = $custocustomFiles->where('folder_id', $newFolderID)->count(); 
                
                //UPDATE NEW Slide
                $newLessonHistory->current_slide    = 1; //when skipped, you lesson will auto index to slide #(1)
                $newLessonHistory->total_slides     =  $filesCounter + $customFilesCounter ; //when skipped, you lesson will auto index to slide #(1)
                

                $newLessonHistory->folder_id        = $newFolderID;
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

                 DB::rollBack();
            }           
        
        } catch (Throwable $e) {

            return false;

            DB::rollBack();
        }
    }



    /**
    * Get the most recent lesson history for a member with a specific status.
    *
    * @param int $memberID The ID of the member.
    * @param string $status The status of the lesson history.
    * @return LessonHistory|false The most recent LessonHistory object if found, false otherwise.
    */
    public function getRecentLessonHistory($memberID, $status) 
    {    
        $lessonHistory = LessonHistory::where('member_id', $memberID)->where('status', $status)->orderBy("time_ended", 'DESC')->first();        
        if ($lessonHistory) { return $lessonHistory; } else { return false; }        
    }

    /**
    * Create a new lesson history record from a skipped lesson.
    *
    * @param int $userID The ID of the user.
    * @param int $lessonScheduleID The ID of the lesson schedule.
    * @param int $newFolderID The ID of the new folder.
    * @param LessonHistory $lessonHistory The existing lesson history object.
    * @return void
    */
    public function createNewHistoryFromSkippedLesson($userID, $lessonScheduleID, $newFolderID, $lessonHistory) 
    {    
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

    /**
    * Update the status of a lesson in the lesson history.
    *
    * @param string $lessonStatus The new lesson status.
    * @param int $scheduleID The ID of the lesson schedule.
    * @param int $memberID The ID of the member.
    * @return bool Whether the lesson status was successfully updated or not.
    */
    public function updateLessonStatus($lessonStatus, $scheduleID, $memberID) 
    { 
        if ($lessonStatus == "INCOMPLETE") 
        {
            $lessonHistory = LessonHistory::where('member_id', $memberID)->where('schedule_id', $scheduleID)->where('status', "COMPLETED")->first();
            if ($lessonHistory) {
                return $lessonHistory->update(['status' => "INCOMPLETE"]);
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
    * Retrieves the parent history item for a given schedule ID.
    *
    * @param int $scheduleID The ID of the schedule.
    * @return object Returns an object with the following properties:
    *   - 'isMerged': Indicates whether the item is merged or not.
    *   - 'parentHistoryID': The ID of the parent history item. Returns null if there is no parent.
    *   - 'lessonHistory': An object representing the lesson history.
    */
    public function getParentHistoryItem($scheduleID) {
    
        $historyItem = $this->where('schedule_id', $scheduleID)
                        //->where('member_id', Auth::user()->id)
                        ->orderBy('id','DESC')
                        ->first();

        if ($historyItem) {   

            if (!isset($historyItem->parent_lesson_id) || $historyItem->parent_lesson_id == '') {

                $isMerged = false;
                $parentHistoryID = null;
                
                //this is the first schedule
                $lessonHistory = $historyItem;

            } else {

                $parent = $this->where('id', $historyItem->parent_lesson_id)
                        //->where('member_id', Auth::user()->id)
                        ->orderBy('id','DESC')
                        ->first();

                if (!$parent)  {
                
                    $isMerged = false;
                    $parentHistoryID = null;

                    //this is the first schedule
                    $lessonHistory = $historyItem;                
                
                } else {
                
                    $lessonHistory = $parent->where('schedule_id', $parent->schedule_id)
                            //->where('member_id', Auth::user()->id)
                            ->orderBy('id','DESC')
                            ->first();   

                    $isMerged = true;
                    $parentHistoryID = $parent->schedule_id; 

                }

            }
        } else {
        
            $isMerged = false;
            $parentHistoryID = null;

            //this is the first schedule
            $lessonHistory = $historyItem;     
        }       

        $result = (object) [
            'isMerged' => $isMerged,
            'parentHistoryID' => $parentHistoryID,
            'lessonHistory' => $lessonHistory
        ];

        return $result;
        
            
    }

    /**
    * Get lesson history for a member with no ratings.
    *
    * @param int $memberID The ID of the member.
    * @return \Illuminate\Database\Eloquent\Collection A collection of LessonHistory objects.
    */
    public function getMemberLessonsWithNoRatings($memberID) 
    {
        $scheduleItem = new ScheduleItem();

        $lessonHistory = LessonHistory::where('lesson_history.member_id', $memberID)
            ->where(function ($query) {
                $query->where('status', 'COMPLETED')
                    ->orWhere('status', 'INCOMPLETE');
            })
            ->leftJoin('schedule_item', 'lesson_history.schedule_id', '=', 'schedule_item.id')
            ->leftJoin('lesson_survey', 'lesson_history.schedule_id', '=', 'lesson_survey.schedule_id')
            ->select('lesson_history.*', 'schedule_item.lesson_time')
            ->whereNull('lesson_survey.schedule_id')
            ->orderBy('lesson_time', 'DESC')
            ->get();   

        foreach ($lessonHistory as $lesson) {

            //tutor info
            $tutor = Tutor::where('user_id', $lesson->tutor_id)->first();

            $lesson->tutorName             = $tutor->user->firstname;
            $lesson->tutorProfileImage = url($tutor->image());


            //lesson date info
            $lesson->lesson_time = $lesson->lesson_time;
            $lesson->lesson_date = $lesson->lesson_time;
            $lesson->duration = $scheduleItem->getLessonTimeDuration($lesson->schedule_id);

            //japanese formatted
            $lesson->jp_lesson_time = ESIDateTimeFormat($lesson->lesson_time);
            $lesson->jp_lesson_date = ESIDateFormat($lesson->lesson_time); 

        }

        return $lessonHistory;    
    }
}
