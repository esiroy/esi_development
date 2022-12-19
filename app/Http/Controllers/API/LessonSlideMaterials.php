<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth, DB;

use App\Models\Folder;
use App\Models\MemberSelectedLessonSlideMaterial;
use App\Models\File;
use App\Models\FileAudio;


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
        $material = MemberSelectedLessonSlideMaterial::where('schedule_id', $scheduleID)->where('user_id', $memberID)->first();

        if ($material) {

            //@done: get the folder materials
            $folderID       = $material->folder_id;
            $folderSegments = Folder::getURLSegments( $material->folder_id, " > ");
            $folderURLArray = Folder::getURLArray( $material->folder_id);

            $files          = File::where('folder_id', $folderID)->orderBy('order_id', 'ASC')->get();

            if ($files) 
            {
                $slides = [];

                foreach ($files as $index => $file) {

                    array_push($slides, url($file->path));

                    //make the index same as the slide number
                    $audioFiles[$index+1] = FileAudio::where('file_id', $file->id)->get(['id', 'file_id', 'path', 'file_name']);
                }
            }

          

            //seach for files in folder as images
            
            return Response()->json([
                "success"              => true,
                "files"                => $slides,
                "audioFiles"                => $audioFiles,
                "folderSegments"       => $folderSegments,
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


    public function saveSelectedLessonSlideMaterial(Request $request, MemberSelectedLessonSlideMaterial $memberSelectedLessonSlideMaterial) 
    {
        $userID             = $request->userID;
        $lessonScheduleID   = $request->lessonID;
        $selectedOption     = $request->selectedOption;

        if ($selectedOption['value'] !== null) 
        {

            $memberSelectedLessonSlideMaterial->saveSelectedLesson($userID, $lessonScheduleID, $selectedOption);   

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
