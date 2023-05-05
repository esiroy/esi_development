<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Homework;
use App\Models\ScheduleItem;

use Auth, App;
use DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;
use Validator;


class HomeworkController extends Controller
{
    public function uploadHomework(Request $request)     
    {

        $scheduleID = $request->lesson_schedule_id;
        $tutorID = $request->tutorID;   
        $reservation =  json_decode($request->reservation);
        $instruction = $request->instruction;


        //check if the schedule is available , if not send an error message
        $scheduleItem = ScheduleItem::find($scheduleID);
        
        if (!$scheduleItem) {
        
          return Response()->json([
                "success" => false,              
                "message" => "We can't find a schedule for your client, the home work was not uploaded.",
            ]);    
        }

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
                'public/uploads/homeworks/', $newFilename
            );

            $imageURL =  url(Storage::url('uploads/homeworks/'. $newFilename));

            //$imageLink = "<a href='$imageURL' target='_blank'><img src='$imageURL' alt='$newFilename' class='img-fluid'></a>";

            $data = [   
                'schedule_item_id' => $scheduleID,
                'member_id' =>  $reservation->member_id,
                'tutor_id' =>   $reservation->tutor_id,
                'filename' =>   $newFilename,
                'original' =>   $path,
                'instruction' => $instruction
            ];
      

          
            $homework = new Homework();
            $memoResponse = $homework->create($data);
        
            return Response()->json([
                "success" => true, 
                "fileName" => $newFilename,
                "path" => $path,
                "data"  => $data,
                "message" => "Homework has been uploaded.",
            ]);    

        } else {
        
              return Response()->json([
                "success" => false,              
                "message" => "Homework was not uploaded.",
            ]);    
            
        }
        
        
    
    }
}
