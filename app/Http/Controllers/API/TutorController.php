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
use App\Models\UserImage;



use Auth, App;
use DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;
use Validator;



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


        $conversations = $memoReply->where('schedule_item_id', $scheduleID)
                        ->orderBy("created_at", 'ASC')
                        ->get();

        if (isset($scheduleItem->memo)) {
            $memo = $scheduleItem->memo;
        } else {
            $memo = null;
        }

        if ($conversations) 
        {            

            $items = [];
            foreach($conversations as $item) {
                $items[] = [
                    "message"       => $item->message, 
                    "message_type"  => $item->message_type,
                    "created_at"    => ESIDateTimeSecondsFormat($item->created_at)
                ];
            }
            
            
           //$memoReply->where('schedule_item_id', $scheduleID)->update(array('is_read' => true));

           $memoReply->where('schedule_item_id', $scheduleID)->where('is_read', false)->where('message_type', "MEMBER")->update(array('is_read' => true));

            return Response()->json([
                "success" => true,  
                "memo"          => $memo,
                "conversations" => $items,            
            ]); 

        } else {
            return Response()->json([
                "success" => false,  
                "memo"          => $memo,
                "message"   => "no conversation found"                
            ]);             
        }
    }


    public function getTutorInbox(Request $request) 
    {

        $tutorID = $request->tutorID;
        $tutorInfo = Tutor::where('user_id',  $tutorID)->first();

        $scheduleItems = new ScheduleItem();
        $memoReply = new MemoReply();     

        $reservations = $scheduleItems->getTutorAllActiveLessons($tutorInfo);

        $ctr = 0;
        $unread = 0;        
        $inbox = array();

        foreach($reservations as $reservation) 
        {              
            
            if (isset($reservation->id)) 
            { 
                $ctr++;

                $latestReply = $memoReply->where('schedule_item_id', $reservation->id)->orderBy('updated_at', 'DESC')->first();

                if ($latestReply) 
                {

                    //GET THE MEMBER COUNT OF UNREAD REPLIES
                    $unreadMemberReplyCount = MemoReply::where('schedule_item_id', $reservation->id)->where('is_read', false)->where('message_type', "MEMBER")->count();

                    //TRACK TOTAL UNREAD
                    $unread = $unread + $unreadMemberReplyCount;

                    //get teacher profile pic
                    $userImageObj = new UserImage;
                    $memberImage = $userImageObj->getMemberPhotoByID($reservation->member_id);         

                    if ($memberImage == null) {
                        $memberOrignalImage = Storage::url('user_images/noimage.jpg');
                    } else {
                        $memberOrignalImage = Storage::url($memberImage->original);
                    }
    
                    if (date('H', strtotime($reservation->lesson_time)) == '00') {
                        $lessonTime = date('Y年 m月 d日 24:i', strtotime($reservation->lesson_time ." - 1 day")) ." - ".   date('24:i', strtotime($reservation->lesson_time." + 25 minutes "));
                    } else {  
                        $lessonTime = date('Y年 m月 d日 H:i', strtotime($reservation->lesson_time)) ." - ".  date('H:i', strtotime($reservation->lesson_time." + 25 minutes "));
                    }                    
        

                    $inbox[] =  array(
                        "schedule_item_id" => $reservation->id,
                        "lessonTime" => $lessonTime,
                        "latestReply" => $latestReply->message,
                        "memberOrignalImage" => $memberOrignalImage,
                        "unreadMessageCount" => $unreadMemberReplyCount
                    );
                }
            }            
        }    

        return Response()->json([
            "success" => true,    
            "inbox" => $inbox,
            "inboxCount" => $ctr,            
            "unread" => $unread,
            "message" => "Member memo replies has been fetched.",
        ]);
        

    }
    
    
    public function getTutorInbox_standard(Request $request) 
    {

        $tutorID = $request->tutorID;
        $tutorInfo = Tutor::where('user_id',  $tutorID)->first();

        $scheduleItems = new ScheduleItem();
        $memoReply = new MemoReply();     

        $reservations = $scheduleItems->getTutorAllActiveLessons($tutorInfo);

        $unread = 0;
        
        $inbox = array();

        foreach($reservations as $reservation) 
        {              
            
            if (isset($reservation->id)) 
            {
                $latestReply = $memoReply->where('schedule_item_id', $reservation->id)
                                          ->where('is_read', false)
                                          ->where('message_type', "MEMBER")
                                          ->orderBy('updated_at', 'DESC')->first();
            
                if ($latestReply) 
                {
                    $unread++;

                    //get teacher profile pic
                    $userImageObj = new UserImage;
                    $memberImage = $userImageObj->getMemberPhotoByID($reservation->member_id);         

                    if ($memberImage == null) {
                        $memberOrignalImage = Storage::url('user_images/noimage.jpg');
                    } else {
                        $memberOrignalImage = Storage::url($memberImage->original);
                    }
    
                    if (date('H', strtotime($reservation->lesson_time)) == '00') {
                        $lessonTime = date('Y年 m月 d日 24:i', strtotime($reservation->lesson_time ." - 1 day")) ." - ".   date('24:i', strtotime($reservation->lesson_time." + 25 minutes "));
                    } else {  
                        $lessonTime = date('Y年 m月 d日 H:i', strtotime($reservation->lesson_time)) ." - ".  date('H:i', strtotime($reservation->lesson_time." + 25 minutes "));
                    }                    
        

                    $inbox[] =  array(
                        "schedule_item_id" => $reservation->id,
                        "lessonTime" => $lessonTime,
                        "latestReply" => $latestReply->message,
                        "memberOrignalImage" => $memberOrignalImage
                    );
                }
            }            
        }    

        return Response()->json([
            "success" => true,    
            "inbox" => $inbox,
            "unread" => $unread,
            "message" => "Member memo replies has been fetched.",
        ]);
        

    }


    public function getUnreadMemberMessages(Request $request) 
    {
        $scheduleID = $request->scheduleID;

        $memoReply = new MemoReply();
        $conversations = $memoReply->where('schedule_item_id', $scheduleID)->where('is_read', false)->where('message_type', "MEMBER")->get();   

        MemoReply::where('schedule_item_id', $scheduleID)->where('is_read', false)->where('message_type', "MEMBER")->update(array('is_read' => true));


        $items = [];
        foreach($conversations as $item) {
            $items[] = [
                "message"       => $item->message,  
                "message_type"  => "MEMBER",          
                "created_at"    => ESIDateTimeSecondsFormat($item->created_at)
            ];
        }

        return Response()->json([
            "success" => true,    
            "conversations" => $items,
            "message" => "Teacher memo replies has been fetched.",
        ]);
    }    

    
    public function sendMemoReply(Request $request) 
    {        
        $scheduleID = $request->scheduleID;
        $tutorID = $request->tutorID;
        $message = linkify($request->message);

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

            $memo = $memoReply->find($memoResponse->id);

            return Response()->json([
                "success"   => true,
                "response"  => "message has been sent!",
                "message"   => $message,            
                 "created_at"      => ESIDateTimeSecondsFormat($memo->created_at),
            ]);
        } else {
            return Response()->json([
                "success"   => false,
                "response"  => "Error has was not sent due to an error, please check back later.",
                "created_at"      => date('m-d-y H:i:s'),
            ]);

        }       
    }

    public function uploadTutorFile(Request $request)     
    {

        $scheduleID = $request->scheduleID;
        $tutorID = $request->tutorID;               


        //check if the schedule is available , if not send an error message
        $scheduleItem = ScheduleItem::find($scheduleID);
        
        
        if ($files = $request->file('file')) 
        {

            //file path
            $originalPath = 'storage/uploads/';

            $newFilename = time()."_". preg_replace('/\s+/', '_', $files->getClientOriginalName());

            $newFilename = mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $newFilename);
            
            // Remove any runs of periods (thanks falstro!)
            $newFilename = mb_ereg_replace("([\.]{2,})", '', $newFilename);

            //save in storage -> storage/public/uploads/
            $path = $request->file('file')->storeAs(
                'public/uploads/memo/', $newFilename
            );

            $imageURL =  url(Storage::url('uploads/memo/'. $newFilename));

            $imageLink = "<a href='$imageURL' target='_blank'><img src='$imageURL' alt='$newFilename' class='img-fluid'></a>";



            $data = [
                'schedule_item_id' => $scheduleID,
                'sender_id' => $tutorID,
                'recipient_id' => $scheduleItem->member_id,
                'message_type' => "TUTOR",
                'message' => $imageLink,
                'is_read' => false,
            ];

            $memoReply = new MemoReply();
            $memoResponse = $memoReply->create($data);
        
        
        }        
        
        
        return Response()->json([
            "success" => true, 
            "fileName" => $newFilename,
            "path" => $path,
            "message" => "Teacher file has been uploaded.",
        ]);        
    }

}