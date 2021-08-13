<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\ChatSupportHistory;


class ChatSupportController extends Controller
{

    public function getChathistory(Request $request, ChatSupportHistory $chatSupportHistory) 
    {
      
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
