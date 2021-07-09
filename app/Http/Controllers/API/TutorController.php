<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Shift;
use App\Models\Tutor;
use App\Models\FavoriteTutor;
use App\Models\ScheduleItem;
use App\Models\MemoReply;



class TutorController extends Controller
{
    public function getTutors(Request $request, User $user)
    {
        //Updated: Remove terminated tutor on the list
        $tutors = Tutor::where('lesson_shift_id', $request['shift_id'])
        ->where('is_terminated', 0)
        ->join('users', 'users.id', '=', 'tutors.user_id')
        ->orderBy('sort', 'ASC')
        ->select('tutors.*', 'users.firstname', 'users.lastname', 'users.valid')
        ->where('valid', 1)
        ->get();

        return Response()->json([
            "success" => true,  
            "tutors" => $tutors,            
        ]);  
    }

    public function getFavoriteTutors(Request $request, FavoriteTutor $favoriteTutor)
    {

        $favoriteTutors = FavoriteTutor::where('user_id', $request->memberID)->orderBy('sequence_number', 'ASC')->get();

        return Response()->json([
            "success" => true,  
            "message"   => "List of tutors successfully fetched",
            "favoriteTutors" => $favoriteTutors,            
        ]); 
        
    }


    public function saveFavoriteTutor(Request $request) 
    {

        $sequence_number = FavoriteTutor::where('user_id', $request->memberID)->count() + 1;
        
        $data = [
                    'valid'     => true,
                    'user_id'    => $request->memberID,
                    'tutor_id'   => $request->tutorID,
                    'sequence_number'   => $sequence_number,
                ];

        $favoriteTutor = FavoriteTutor::create($data);

        if ($favoriteTutor) {
            return Response()->json([
                "success" => true,
                "message" => "Favorite Tutor has been created"
            ]); 
        } else {
            return Response()->json([
                "success" => false,
                "message" => "Can not save Favorite Tutor, please try again later."
            ]);             
        }         
    }


    public function removeFavoriteTutor(Request $request) 
    {
        $user_id = $request->memberID;
        $tutor_id = $request->tutorID;

        //used get to it will also delete duplicated items
        $favoriteTutor = FavoriteTutor::where('user_id', $user_id)->where('tutor_id', $tutor_id)->get();

        foreach ($favoriteTutor as $deleteItem) {
            $deleteItem->delete();
        }

        return Response()->json([
            "success" => true,
            "message" => "Tutor has been removed from your favorite list"
        ]);         

    }

    public function getMemoConversations(Request $request) 
    {
        $scheduleID = $request->scheduleID;
        $tutorID = $request->tutorID;
        $message = $request->message;

        //check if the schedule is available , if not send an error message
        $scheduleItem = ScheduleItem::find($scheduleID);
        
        $memoReply = new MemoReply();
        $conversations = $memoReply->where('schedule_item_id', $scheduleID)->get();     

        if ($conversations) 
        {            
           $memoReply->where('schedule_item_id', $scheduleID)->update(array('is_read' => true));


            return Response()->json([
                "success" => true,  
                "message"   => "conversations succesfully fetched",
                "conversations" => $conversations,            
            ]); 
        } else {
            return Response()->json([
                "success" => false,  
                "message"   => "no conversation found"                
            ]);             
        }
    }


    public function getUnreadMemberMessages(Request $request) 
    {
        $scheduleID = $request->scheduleID;

        $memoReply = new MemoReply();
        $conversations = $memoReply->where('schedule_item_id', $scheduleID)->where('is_read', false)->where('message_type', "MEMBER")->get();   

        MemoReply::where('schedule_item_id', $scheduleID)->where('is_read', false)->where('message_type', "MEMBER")->update(array('is_read' => true));

        return Response()->json([
            "success" => true,    
            "conversations" => $conversations,
            "message" => "Teacher memo replies has been fetched.",
        ]);
    }    

    
    public function sendMemoReply(Request $request) 
    {        
        $scheduleID = $request->scheduleID;
        $tutorID = $request->tutorID;
        $message = $request->message;

        //check if the schedule is available , if not send an error message
        $scheduleItem = ScheduleItem::find($scheduleID);

        $data = [
                'schedule_item_id' => $scheduleID,
                'sender_id' => $tutorID,
                'recipient_id' => $scheduleItem->member_id,
                'message_type' => "TUTOR",
                'message' => $message,
                'is_read' => false,
            ];

        $memoReply = new MemoReply();
        $memoResponse = $memoReply->create($data);

        if ($memoResponse) 
        {
            return Response()->json([
                "success"   => true,
                "response"  => "message has been sent!",
                "message"   => $message,            
                "date"      => date('m-d-y'),
            ]);
        } else {
            return Response()->json([
                "success"   => false,
                "response"  => "Error has was not sent due to an error, please check back later.",
                "date"      => date('m-d-y'),
            ]);

        }       
    }


}