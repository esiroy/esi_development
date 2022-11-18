<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth, DB;

use App\Models\Folder;
use App\Models\MemberSelectedLessonSlideMaterial;
use App\Models\File;


class LessonSlideMaterials extends Controller
{

    /*@description: get the lesson slides of the folder */

    
    public function getLessonMaterialSlides(Request $request)
    {
        $scheduleID = $request->scheduleID;
        $memberID     = $request->memberID;

        /*
            @todo: get the selected member (lesson_id)
                 : if the member has not selected return false
        */

        $material = MemberSelectedLessonSlideMaterial::where('schedule_id', $scheduleID)
                                                        ->where('user_id', $memberID)
                                                        ->first();

        if ($material) {

            //@done: get the folder materials
            $folderID       = $material->folder_id;
            $folderSegments = Folder::getURLSegments( $material->folder_id, " > ");
            $folderURLArray = Folder::getURLArray( $material->folder_id);
            $files          = File::where('folder_id', $folderID)->get();

            if ($files) {

                $slides = [];
            
                foreach ($files as $file) {

                    array_push($slides, url($file->path));

                }
            }

          

            //seach for files in folder as images
            
            return Response()->json([
                "success"              => true,
                "files"                => $slides,
                "folderSegments"       =>  $folderSegments,
                "folderURLArray"       => $folderURLArray
            ]);  

        
        }
    }

    /*
        @description: Get the folder with heirchy for file manager
    */
    public function getLessonSlideMaterialList(Request $request) 
    {
        return Response()->json([
            "success"               => true,
            "folders"               => Folder::getPrivateFolders()
        ]);    
    }


    public function saveSelectedLessonSlideMaterial(Request $request, MemberSelectedLessonSlideMaterial $MemberSelectedLessonSlideMaterial) 
    {
        $userID             = Auth::user()->id;
        $lessonScheduleID   = $request->lessonID;
        $selectedOption     = $request->selectedOption;

        if ($selectedOption['value'] !== null) 
        {

            $MemberSelectedLessonSlideMaterial->saveSelectedLesson($userID, $lessonScheduleID, $selectedOption);   

            return Response()->json([
                "success"       => true,
                "userID"        => $userID,
                "folderID"      => $selectedOption['id'],
                "folderName"    => $selectedOption['name'],
                "message"       => "Lesson Material has been successfully saved"
            ]);  

        } else {
        
            return Response()->json([
                "success"       => false,
                "message"       => "Please select a lesson"
            ]);           
        }




    }
    

}
