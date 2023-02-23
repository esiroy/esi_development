<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\LessonChat;
use App\Models\User;
use App\Models\UserImage;
use Auth;


class lessonChatController extends Controller
{
    public function getLessonChatHistory(Request $request, LessonChat $lessonChat) 
    {

        //$page = $request->page;
        $lessonid      = $request->channelid;
        $sender_id      = $request->sender_id;
        $recipient_id   = $request->recipient_id;  


        $readChatHistoryItems = $lessonChat
                                ->select('lesson_chat_history.*', 'users.id', 'users.firstname', 'members.nickname')
                                ->where('lesson_id', $lessonid)
                                ->where('is_read', true)
                                ->leftJoin('users', 'users.id', '=', 'lesson_chat_history.sender_id')
                                ->leftJoin('members', 'members.user_id', '=', 'lesson_chat_history.sender_id')
                                ->orderby('lesson_chat_history.id', "DESC")->get();

                                
        $unreadChatHistoryItems = $lessonChat
                                ->select('lesson_chat_history.*', 'users.id', 'users.firstname', 'members.nickname')
                                ->where('lesson_id', $lessonid)
                                ->where('is_read', false)
                                ->leftJoin('users', 'users.id', '=', 'lesson_chat_history.sender_id')
                                ->leftJoin('members', 'members.user_id', '=', 'lesson_chat_history.sender_id')
                                ->orderby('lesson_chat_history.id', "DESC")->get();                                
            
        $allhistory = $unreadChatHistoryItems->count() + $readChatHistoryItems->count();           

        if ( $allhistory > 0)  {
            return Response()->json([
                "success"                       => true,                
                "unreadChatHistoryItems"        => $unreadChatHistoryItems,
                "readChatHistoryItems"          => $readChatHistoryItems,   
                
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


    public function markLessonChatMessagesRead(Request $request, LessonChat $lessonChat) 
    {       
        $lessonid       = $request->channelid;
        $unread         = $lessonChat->where('lesson_id', $lessonid)->where('recipient_id', Auth::user()->id)->where('is_read', false);

        if ($unread) {
        
            $unread->update([
                'is_read' => true
            ]);

            return Response()->json([
                "success"  => true,
            ]);

        } else {
            return Response()->json([
                "success"  => false,
            ]);

        }
        
    }

}
