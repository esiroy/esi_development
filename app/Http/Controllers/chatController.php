<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatSupportHistory;

class chatController extends Controller
{
    public function create_chat(ChatSupportHistory $chatSupportHistory) {

                      
        $recentUsers = $chatSupportHistory->where(function($query) 
        {
                $query->where('chatsupport_history.message_type', 'MEMBER') 
                    ->where('chatsupport_history.sender_id', '!=', 1);
        })->orWhere(function($query) {

            $query->where('chatsupport_history.message_type', 'CHAT_SUPPORT') 
                ->where('chatsupport_history.recipient_id', '!=', 1);
        })
        ->distinct()
        ->latest()
        ->get();

        foreach ($recentUsers as $item) {
            $ids[] = $item->sender_id;
            $ids[] = $item->recipient_id;
        }
        
        $uniqueUsers = array_unique($ids); 
  

        foreach ($uniqueUsers as $key => $recentUser) 
        {
            echo $recentUser ."<Br>";
        }

    }
}
