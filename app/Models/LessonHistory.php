<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Auth, DB;
use App\Models\Folder;
use App\Models\File;
use App\Models\CustomTutorLessonMaterials;
 
use Carbon\Carbon;


class LessonHistory extends Model
{
    public $table = 'lesson_history';

    public $timestamps = false;

    protected $guarded = array('created_at', 'updated_at');


    /**    
        @description:   - SKIP AND CREATE 'NEW' STATUS LESSON
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
        @description:   - GET NEW LESSON       
    */

    public function getRecentLessonHistory($memberID, $status) 
    {    
        $lessonHistory = LessonHistory::where('member_id', $memberID)->where('status', $status)->orderBy("time_ended", 'DESC')->first();        
        if ($lessonHistory) { return $lessonHistory; } else { return false; }        
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

}
