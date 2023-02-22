<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\LessonChat;
use App\Models\User;
use App\Models\UserImage;


class lessonChatController extends Controller
{
    public function getLessonChatHistory(Request $request, LessonChat $lessonChat) 
    {

        //$page = $request->page;
        $lessonid      = $request->channelid;
        $sender_id      = $request->sender_id;
        $recipient_id   = $request->recipient_id;

        $chatHistoryItems = $lessonChat
                                ->select('lesson_chat_history.*', 'users.id', 'users.firstname', 'members.nickname')
                                ->where('lesson_id', $lessonid)
                                ->leftJoin('users', 'users.id', '=', 'lesson_chat_history.sender_id')
                                ->leftJoin('members', 'members.user_id', '=', 'lesson_chat_history.sender_id')
                                ->orderby('lesson_chat_history.id', "DESC")->get();
                              

        if ($chatHistoryItems->count() > 0)  {
            return Response()->json([
                "success"           => true,                
                "chatHistoryItems"  => $chatHistoryItems,                
            ]);
        } else {
            return Response()->json([
                "success"           => false,                
                "message"           => "no more history found"
            ]);            
        }
    }


    //Save Customer Chat Chat
    public function saveLessonChat(Request $request, LessonChat $LessonChat)
    {
        $data = [
            'lesson_id'     => $request->channelid,
            'sender_id'     => $request->sender_id,
            'recipient_id'  => $request->recipient_id,
            'message'       => $request->message,
            'message_type'  => $request->message_type,
            'is_read'       => $request->is_read,
            'valid'         => $request->valid,
        ];      

        $chatSupportItem = $LessonChat->create($data);

        if ($chatSupportItem) 
        {
            return Response()->json([
                "success"   => true,
                "response"  => "message has been sent!",
                "message"   =>  $request->message,            
                "date"      => date('m-d-y'),
            ]);

        } else {
            return Response()->json([
                "success"   => false,
                "response"  => "Error has was not sent due to an error, please check back later.",
                "date"      => date('m-d-y'),
            ]);

        }  


        return Response()->json([
            "success"  => true,
        ]);

    }

}
