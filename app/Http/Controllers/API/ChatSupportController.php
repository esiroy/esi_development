<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\ChatSupportHistory;
use App\Models\User;
use App\Models\UserImage;
use DB, Str;

class ChatSupportController extends Controller
{



    public function getAdminChatHistory(Request $request, ChatSupportHistory $chatSupportHistory) 
    {

        $page = $request->page;
        $sender_id = $request->sender_id;
        $recipient_id = $request->recipient_id;

        if ($page == null) {
            $page = 1;
        }

        $itemsPerPage = 10;

        
        $chatHistoryItems =  $chatSupportHistory
        ->where('sender_id', $sender_id)
        ->where('valid', 1)
        ->where('is_read', 1)
        ->orderby('id', "DESC")->orWhere(function ($q) use ($sender_id) 
        {
                //show my from member
                $q->orWhere('recipient_id', $sender_id)                
                ->where('valid', 1)
                //->where('is_read', 1)
                ->orderby('id', "DESC");

        })->paginate($itemsPerPage, ['*'], 'page', $page);    

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

    /* ADMIN GET UNREAD MESSAGES */
    public function getAdminUnreadChatMessages(Request $request, ChatSupportHistory $chatSupportHistory) 
    {
        $sender_id = $request->sender_id;

       
        $chatItems = $chatSupportHistory
                            //->where('message_type', 'MEMBER')
                            ->where('sender_id', $sender_id)
                            ->where('recipient_id', 1 )
                            ->where('valid', 1)
                            ->where('is_read', 0)
                            ->orderby('id', "DESC")->get();

        if ($chatItems->count() > 0)  {
            return Response()->json([
                "success"                   => true,                
                "chatItems"                 => $chatItems,
                "unreadMessageCount"        => $chatItems->count(),    
                "message"                   => "Unread Messages Found"            
            ]);
        } else {
            return Response()->json([
                "success"               => false,            
                "unreadMessageCount"    => 0,       
                "message"               => "No Unread Messages found"
            ]);            
        }                  
    }


    public function getChathistory(Request $request, ChatSupportHistory $chatSupportHistory) 
    {

        $page = $request->page;
        $sender_id = $request->sender_id;
        $recipient_id = $request->recipient_id;

        if ($page == null) {
            $page = 1;
        }

        $itemsPerPage = 5;

        $chatHistoryItems = $chatSupportHistory
                            ->where('sender_id', $sender_id)
                            ->orWhere('recipient_id', $sender_id)
                     
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

    /* 
        CUSTOMER SUPPORT USER LIST
        GET ALL THE MEMBERS THAT RECENTLY CHATTED 
    */
    public function getRecentUserChatList(Request $request, UserImage $userImage, ChatSupportHistory $chatSupportHistory) 
    {

        $userList =  [];

        
        //user that has chatted to chat support
        $recentUsers_sender = $chatSupportHistory
                        ->select('sender_id as userid', 'created_at')
                        ->orderBy('created_at', 'DESC')
                        ->where('chatsupport_history.message_type', 'MEMBER')   
                        ->where('chatsupport_history.sender_id', '!=', 1)
                        ->latest()
                        ->distinct()
                        ->pluck('userid')
                        ->toArray();
        
        //Append user with sent messages from chat support
        $recentUsers_recipient = $chatSupportHistory
                        ->select('recipient_id as userid', 'created_at')
                        ->orderBy('created_at', 'DESC')
                        ->where('chatsupport_history.message_type', 'CHAT_SUPPORT')
                        ->where('chatsupport_history.recipient_id', '!=', 1)
                        ->latest()
                        ->distinct()
                        ->pluck('userid')
                        ->toArray();
                        


        /*
                $recentUsers = $chatSupportHistory->select('recipient_id as userid', 'created_at')->where(function($query) {

                        $query->select('sender_id as userid', 'created_at')
                                ->where('chatsupport_history.message_type', 'MEMBER') 
                                ->where('chatsupport_history.sender_id', '!=', 1)
                                ->pluck('userid')
                                ->toArray();
                                

                    })->orWhere(function($query) {


                        $query->select('recipient_id as userid', 'created_at')
                                ->where('chatsupport_history.message_type', 'CHAT_SUPPORT') 
                                ->where('chatsupport_history.recipient_id', '!=', 1)
                                ->pluck('userid')
                                ->toArray();                                

                    })->orderBy('created_at', 'DESC')
                      ->pluck('userid')
                      ->toArray();
            */
                        
                      


    
                                                               
        

        $recentUsers = array_merge($recentUsers_sender, $recentUsers_recipient); 
        $uniqueUsers = array_unique($recentUsers);   

        foreach ($uniqueUsers as $key => $recentUser) 
        {

            $user = User::find($recentUser);

            if ($user) {
            
                $unread = $chatSupportHistory
                                ->where('message_type', 'MEMBER')->where('sender_id', $user->id)
                                ->where('valid', 1)
                                ->where('is_read', 0)
                                ->orderby('id', "DESC");
            
                $sentTotalMsg = $chatSupportHistory->where('message_type', 'MEMBER')
                                ->where('sender_id', $user->id)
                                ->where('recipient_id', 1)
                                ->where('valid', 1)
                                ->orderby('id', "DESC")->count();

                $recievedTotalMsg = $chatSupportHistory->where('message_type', 'CHAT_SUPPORT')
                                ->where('sender_id', 1)
                                ->where('recipient_id', $user->id)
                                ->where('valid', 1)
                                ->orderby('id', "DESC")->count();
                                                                

                $totalMsg = $sentTotalMsg + $recievedTotalMsg;

                $unreadMsgCtr = $unread->count(); 

                $recentMsg = $unread->limit(1)->first();

                //set messages from CHAT_SUPPORT
                $supportMsg = $chatSupportHistory->where('message_type', 'CHAT_SUPPORT')
                                                ->where('sender_id', 1)
                                                ->where('recipient_id', $user->id)                                                
                                                ->where('valid', 1)
                                                ->orderby('id', "DESC")->count();

                $userList[$key]['id']           = $user->id;
                $userList[$key]['userid']       = $user->id;
                $userList[$key]['username']     = $user->username;
                $userList[$key]['nickname']     = $user->memberInfo->nickname ?? '-';         
                $userList[$key]['status']       = 'offline';
                $userList[$key]['type']         = $user->user_type;

                $userList[$key]['totalMsg']     = $totalMsg;
                $userList[$key]['unreadMsg']    = $unreadMsgCtr;
                //add most recent message
                $userList[$key]['recentMsg']    = Str::limit($recentMsg->message  ?? null, 30) ;

                $userList[$key]['supportMsg']    = $supportMsg; //customer support messages

                $userPhoto = $userImage->getMemberPhotoByID($user->id);

                if ($userPhoto) {

                    if (file_exists(public_path('storage/'. $userPhoto->original)))
                        $userList[$key]['user_image']  = asset('storage/'. $userPhoto->original);
                    else
                    $userList[$key]['user_image']  = asset('images/samplePictureNoImage.jpg');
                } else {            
                    $userList[$key]['user_image']  = asset('images/samplePictureNoImage.jpg');
                }              


            }
        }

        if (count($userList) > 0)  {
            return Response()->json([
                "success"           => true,                
                "recentUsers"       => $userList,
            ]);
        } else {
            return Response()->json([
                "success"           => false,                
                "message"           => "no more user found"
            ]);            
        }
    }





    public function markAdminChatMessagesRead(Request $request, ChatSupportHistory $chatSupportHistory)
    {

        $userID = $request->userID;
        $message_type = $request->message_type;

        $chatItems = $chatSupportHistory
                    ->where('message_type', $message_type)
                    ->where('sender_id', $userID)
                    ->where('valid', 1)
                    ->where('is_read', 0)
                    ->update(['is_read' => 1]);

        if ($chatItems)  {
            return Response()->json([
                "success"               => true, 
                "message"               => "All Unread Messages marked as read"            
            ]);

        } else {
            return Response()->json([
                "success"               => false,   
                "message"               => "No Unread Messages found"
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
                            ->orderby('id', "DESC")
                            ->get();

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
