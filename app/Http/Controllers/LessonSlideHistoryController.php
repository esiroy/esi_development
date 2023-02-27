<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LessonHistory;
use App\Models\LessonSlideHistory;
use App\Models\Folder;
use App\Models\File;
use App\Models\ScheduleItem;
use App\Models\LessonChat;

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
    public function show($lessonHistoryID, LessonHistory $lessonHistory, LessonSlideHistory $lessonSlideHistory, Folder $folder, File $file, LessonChat $lessonChat)
    {     

        $lessonHistory = $lessonHistory->where('schedule_id', $lessonHistoryID)
                        ->where('member_id', Auth::user()->id)
                        ->orderBy('id','DESC')
                        ->first();

        if ($lessonHistory) {

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
            $slides  = $file->where('files.folder_id', $lessonHistory->folder_id)->orderby('files.order_id', 'ASC')->get();

            //@todo: get the
            $slideHistory = $lessonSlideHistory->where('lesson_history_id',  $lessonHistory->id )->get();

         
            $chatHistoryItems = $lessonChat->select('lesson_chat_history.*', 'users.id', 'users.firstname', 'users.user_type','members.nickname')
                                ->where('lesson_chat_history.lesson_id', $lessonHistoryID )                              
                                ->leftJoin('users', 'users.id', '=', 'lesson_chat_history.sender_id')
                                ->leftJoin('members', 'members.user_id', '=', 'lesson_chat_history.sender_id')
                                ->orderby('lesson_chat_history.id', "DESC")->get();

            
            return view('modules.lessonslidehistory.show', compact('lessonHistory', 'lessonTitle', 'slides', 'slideHistory', 'reservationData', 'chatHistoryItems'));

        } else {
        
             abort(403, 'No Lesson Slide History Found');
        }

    }

}
