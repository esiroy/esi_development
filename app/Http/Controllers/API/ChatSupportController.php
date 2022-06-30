<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\ChatSupportHistory;


class ChatSupportController extends Controller
{

    public function getChathistory(Request $request, ChatSupportHistory $chatSupportHistory) {

        $page = $request->page;
        $sender_id = $request->sender_id;
        $recipient_id = $request->recipient_id;

        if ($page == null) {
            $page = 1;
        }

        $itemsPerPage = 15;

        
        $chatHistoryItems = $chatSupportHistory->where('sender_id', $sender_id)->orWhere('recipient_id', $sender_id)
                            ->orderby('id', "DESC")
                            ->paginate($itemsPerPage, ['*'], 'page', $page);

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

    /* @sender_id -  user ID of the user that need that chat unread query */

    public function getUnreadChatMessages(Request $request, ChatSupportHistory $chatSupportHistory) 
    {
        $userID = $request->userID;

        $chatItems = $chatSupportHistory
                            ->where('message_type', 'CHAT_SUPPORT')
                            ->where('recipient_id', $userID)
                            ->where('valid', 1)
                            ->where('is_read', 0)
                            ->orderby('id', "DESC")->get();

        if ($chatItems->count() > 0)  {
            return Response()->json([
                "success"                => true,                
                "chatItems"             => $chatItems,
                "unreadMessageCount"    => $chatItems->count(),    
                "message"               => "Unread Messages Found"            
            ]);
        } else {
            return Response()->json([
                "success"               => false,            
                "unreadMessageCount"    => 0,       
                "message"               => "No Unread Messages found"
            ]);            
        }                  
    }

    public function markChatMessagesRead(Request $request, ChatSupportHistory $chatSupportHistory)
    {

        $userID = $request->userID;
        $message_type = $request->message_type;

        $chatItems = $chatSupportHistory
                    ->where('message_type', $message_type) //->where('message_type', 'CHAT_SUPPORT')
                    ->where('recipient_id', $userID)
                    ->where('valid', 1)
                    ->where('is_read', 0)
                    ->update(['is_read' => 1]);

        if ($chatItems)  {
            return Response()->json([
                "success"               => true, 
                "message"               => "Unread Messages marked as read"            
            ]);

        } else {
            return Response()->json([
                "success"               => false,   
                "message"               => "No Unread Messages found"
            ]);            
        }                       
    }




    public function getAllChathistory(Request $request, ChatSupportHistory $chatSupportHistory) {      
        $sender_id = $request->sender_id;
        $recipient_id = $request->recipient_id;
                 
        $chatHistoryItems = $chatSupportHistory
            ->where('sender_id', $sender_id)
            ->orWhere('recipient_id', $sender_id)
            ->get();
        return Response()->json([
            "success"           => true,
            "chatHistoryItems"  => $chatHistoryItems            
        ]);
    }

    //Save Customer Chat Chat
    public function saveCustomerSupportChat(Request $request, ChatSupportHistory $chatSupportHistory)
    {
        $data = [
            'sender_id'     => $request->sender_id,
            'recipient_id'  => $request->recipient_id,
            'message'       => $request->message,
            'message_type'          => $request->message_type,
            'is_read'       => $request->is_read,
            'valid'         => $request->valid,
        ];      

        $chatSupportItem = $chatSupportHistory->create($data);

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
            "success"               => true,
        ]);

    }
}
