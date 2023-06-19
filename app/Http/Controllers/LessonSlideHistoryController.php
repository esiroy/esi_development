<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LessonHistory;
use App\Models\LessonSlideHistory;
use App\Models\Folder;
use App\Models\File;
use App\Models\ScheduleItem;
use App\Models\LessonChat;
use App\Models\MemberFeedback;
use App\Models\MemberFeedbackDetails;
use App\Models\FileAudio;
use Auth;

class LessonSlideHistoryController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }



    /**
     * Show the Lesson Slide History
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($lessonHistoryID, LessonHistory $lessonHistory, LessonSlideHistory $lessonSlideHistory, 
                        Folder $folder, File $file, LessonChat $lessonChat,
                        MemberFeedback $memberFeedback, MemberFeedbackDetails $memberFeedbackDetails)
    {     

   

        $historyItem = $lessonHistory->where('schedule_id', $lessonHistoryID)
                        ->where('member_id', Auth::user()->id)
                        ->orderBy('id','DESC')
                        ->first();

        if ($historyItem) {
        
            if (!isset($historyItem->parent_lesson_id) || $historyItem->parent_lesson_id == '') {

                $isMerged = false;
                $parentHistoryID = null;
                //this is the first schedule
                $lessonHistory = $historyItem;

            } else {



                $parent = $lessonHistory->where('id', $historyItem->parent_lesson_id)
                        ->where('member_id', Auth::user()->id)
                        ->orderBy('id','DESC')
                        ->first();


                $lessonHistory = $parent->where('schedule_id', $parent->schedule_id)
                        ->where('member_id', Auth::user()->id)
                        ->orderBy('id','DESC')
                        ->first();   

                $isMerged = true;
                $parentHistoryID = $parent->schedule_id;  

            }

        }

     



        if ($lessonHistory) 
        {

            $lessonFolder = $folder->getURLArray($lessonHistory->folder_id);
            $lessonTitle = implode(" > ", $lessonFolder);

            $reserve = ScheduleItem::where('id', $lessonHistory->schedule_id)->first();

            $reservationData = [
                'schedule_id'       => $reserve->id,
                'tutor_id'          => $reserve->tutor_id,
                'member_id'         => $reserve->member_id,
                'duration'          => $reserve->duration,
                'lesson_time'       => $reserve->lesson_time,
                'lessonTimeRage'    => LessonTimeRange($reserve->lesson_time),
                'schedule_status'   => $reserve->schedule_status
            ];   
            //we will get the material images
            $files      = $file->where('files.folder_id', $lessonHistory->folder_id)->orderBy('files.order_id', 'ASC')->get();
            $audioFiles = [];

            if ($files) {

                $slides = [];
                foreach ($files as $index => $file) {
                    array_push($slides, url($file->path));
                    //make the index same as the slide number
                    $audioFiles[$index+1] = FileAudio::where('file_id', $file->id)->get(['id', 'file_id', 'path', 'file_name']);
                    $notes[$index+1]  = $file->notes;
                }           

            } else {            
                $slides = [];
                
            }

            $memberFeedback = $memberFeedback->where('member_feedback.schedule_id', $lessonHistoryID)->get();

            if ($memberFeedback) {            
                foreach($memberFeedback as $index => $feedback) {
                    $feedbackdetails = $memberFeedbackDetails->where('member_feedback_id', $feedback->id)->get();
                    if ($feedbackdetails) {
                        $memberFeedback[$index]['details'] = (object) $feedbackdetails;
                    }                
                }               
            } else {
                  $memberFeedback[$index]['details'] = (object) [];
            }

           

            //Member Feeback

            //@todo: slides
            $slideHistory = $lessonSlideHistory->where('lesson_history_id',  $lessonHistory->id )->orderBy('slide_index', 'ASC')->get();

            //Chat Viewer
            $messages = $lessonChat->select('lesson_chat_history.*', 'users.id', 'users.firstname', 'users.user_type','members.nickname')
                                ->where('lesson_chat_history.lesson_id', $lessonHistoryID )                              
                                ->leftJoin('users', 'users.id', '=', 'lesson_chat_history.sender_id')
                                ->leftJoin('members', 'members.user_id', '=', 'lesson_chat_history.sender_id')
                                ->orderby('lesson_chat_history.id', "DESC")->get();


            return view('modules.lessonslidehistory.show', compact('isMerged', 'parentHistoryID', 'lessonHistory', 
                                'lessonTitle', 'files', 'audioFiles', 'slideHistory', 
                                'reservationData', 'messages', 'memberFeedback'));

        } else {
        
             abort(403, 'No Lesson Slide History Found');
        }

    }

}