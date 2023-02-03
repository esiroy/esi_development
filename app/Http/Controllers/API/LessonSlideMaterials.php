<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth, DB;

use App\Models\Folder;
use App\Models\MemberSelectedLessonSlideMaterial;
use App\Models\File;
use App\Models\FileAudio;

use App\Models\LessonHistory;
use App\Models\LessonSlideHistory;
use App\Models\CustomTutorLessonMaterials;


class LessonSlideMaterials extends Controller
{


    public function saveEmptyCustomSlide(Request $request) { 

       $createdCustomLesson = CustomTutorLessonMaterials::create([
            'lesson_schedule_id'    => $request->scheduleID,
            'folder_id'             => $request->folderID,
            'file_name'             => "EMPTY",
            'upload_name'           => "EMPTY",
            'path'                  => null,       
            'size'                  => 0,
            'order_id'              => $request->slideIndex
        ]);

        if ($createdCustomLesson) {
        

            return Response()->json([
                "success"                => true,
                "message"                => "Custom Lesson Material has been successfully added"
            ]);         
        
        } else {
        
        
            return Response()->json([
                "success"                => false,
                "message"                => "Error :: Custom Lesson Material was not added due to an errord"
            ]); 
        }


    }

    
    //@description: determine if the user pre-selected a lesson
    public function getMemberLessonSelected(Request $request, MemberSelectedLessonSlideMaterial $memberSelectedLessonSlideMaterial) {

        $userID             = $request->userID;
        $lessonScheduleID   = $request->lessonID;
      
        $memberSelectedLesson = $memberSelectedLessonSlideMaterial->selectedLesson($userID, $lessonScheduleID);
        
        if ($memberSelectedLesson) {

            return Response()->json([
                "success"                => true,
                "memberSelectedLesson"   => $memberSelectedLesson,
                "message"                => "Member selected Lesson Material has been successfully fetched"
            ]); 

        } else {

            return Response()->json([
                "success"                => false,
                "memberSelectedLesson"   => $memberSelectedLesson, 
                "message"                => "Member Selected Lesson Material not found"
            ]); 

        }
    }


    /**
        @description :   update the selected lesson so it can be the basis the "new slide"
        @param: @request->userID 
        @param: @request->userID 
    */
    public function updateSelectedLesson(Request $request, MemberSelectedLessonSlideMaterial $memberSelectedLessonSlideMaterial, LessonHistory $lessonHistory) {

        $userID             = $request->userID;
        $lessonScheduleID   = $request->lessonID;
        $newFolderID        = $request->folderID;

        $lessonSelected     = $memberSelectedLessonSlideMaterial->where('user_id', $userID)->where('schedule_id', $lessonScheduleID)->first();

        if (!$lessonSelected) {

            $created = $memberSelectedLessonSlideMaterial->create([
                    'user_id'       => $userID,
                    'schedule_id'   => $lessonScheduleID,
                    'folder_id'     => $newFolderID,            
            ]);

            $isLessonSkipped = $lessonHistory->skipLesson($userID, $lessonScheduleID, $newFolderID);
            
            return Response()->json([
                "success"           => true,
                'newFolderID'       => $newFolderID,
                "created"           => $created,
                "message"           => "Member selected Lesson Material has been successfully created"
            ]); 


        } else if ($lessonSelected) {
        
            $updated = $lessonSelected->update([
                'folder_id' => $newFolderID
            ]);

            if ($updated) {


                $isLessonSkipped = $lessonHistory->skipLesson($userID, $lessonScheduleID, $newFolderID);


                return Response()->json([
                    "success"           => true,                    
                    'newFolderID'       => $newFolderID,
                    "message"           => "Member selected Lesson Material has been successfully updated"
                ]); 

            } else {
            
                return Response()->json([
                    "success"                => false,                
                    "message"                => "Member Selected Lesson Material was not updated due to an error"     
                ]); 
            }
        
        } else {
        
            return Response()->json([
                "success"                => false,                
                "message"                => "Member Lesson Material Selection encountered an error, please try again later"     
            ]);         
        }


    }


    /** 
        @description: get the lesson material slides of the folder, with auto selection
    */    
    public function getLessonMaterialSlidesAutoNextFolder(Request $request, Folder $folder, MemberSelectedLessonSlideMaterial $memberSelectedMaterial)
    {
        $scheduleID         = $request->scheduleID;
        $memberID           = $request->memberID;

        $selectedMaterial = $memberSelectedMaterial->where('schedule_id', $scheduleID)->where('user_id', $memberID)->first();

        //get Lesson History details
        $lessonHistory = LessonHistory::where('member_id', $memberID)->where('schedule_id', $scheduleID)->where('status', "NEW")->first();


        if ($selectedMaterial) {        
            $folderID       = $selectedMaterial->folder_id;
        } else {        

           
            

                $folderID       = $folder->getNextFolderID($memberID);

           
            
        }


        if ($lessonHistory) {
            //Initialzie Audio Objects
            for($ctr= 0; $ctr <=  $lessonHistory->total_slides ; $ctr ++) {            
                $audioFiles[$ctr+1] =  [];
            }
        } 

        //Folder Segment
        $folderSegments     = Folder::getURLSegments($folderID, " > ");
        $folderURLArray     = Folder::getURLArray($folderID);


       //get Lesson History details
        $lessonHistory = LessonHistory::where('member_id', $memberID)->where('schedule_id', $scheduleID)->where('status', "NEW")->first();
        if ($lessonHistory) {
            //Initialzie Audio Objects
            for($ctr= 0; $ctr <=  $lessonHistory->total_slides ; $ctr ++) {            
                $audioFiles[$ctr+1] =  [];
            }
        } 


        //SLIDES HISTORY
        $lessonSlideHistory = new LessonSlideHistory();
        $slideHistory       = $lessonSlideHistory->getAllSlideHistory($scheduleID);

        
        $files              = File::where('folder_id', $folderID)->orderBy('order_id', 'ASC')->get();
        $customFiles        = CustomTutorLessonMaterials::where('lesson_schedule_id', $scheduleID)->where('folder_id', $folderID)->orderBy('order_id', 'ASC')->get(); 


        if (isset($newFolderID)) {   

            //$selectedLesson['id'] = $newFolderID;
            //$res = $memberSelectedMaterial->saveSelectedLesson($memberID, $scheduleID, $selectedLesson);    

            $folderID = $newFolderID;    

        } else if (isset($folderID)) {
        
           // $selectedLesson['id'] = $folderID;
           // $res = $memberSelectedMaterial->saveSelectedLesson($memberID, $scheduleID, $selectedLesson);   

        } else {

            return Response([
                "success"           => false,
                "message"           => "No slides for this lesson",  
                "slideHistory"      => $slideHistory ?? null,
                "message"           => "No slides for this lesson"
            ]);         
              
        }
        

        
        $files              = File::where('folder_id', $folderID)->orderBy('order_id', 'ASC')->get();
        $customFiles        = CustomTutorLessonMaterials::where('lesson_schedule_id', $scheduleID)->where('folder_id', $folderID)->orderBy('order_id', 'ASC')->get(); 
        



        //@note: lesson slide history
        $lessonSlideHistory = new LessonSlideHistory();
        $slideHistory = $lessonSlideHistory->getAllSlideHistory($scheduleID);


        if ($files) {
            $slides = [];
            foreach ($files as $index => $file) {
                array_push($slides, url($file->path));
                //make the index same as the slide number
                $audioFiles[$index+1] = FileAudio::where('file_id', $file->id)->get(['id', 'file_id', 'path', 'file_name']);
            }                
        } else {            
            $slides = [];
        }


        //seach for files in folder as images
        return Response([
                    "success"              => true,
                    'folderID'             => $folderID, 
                    "folderSegments"       => $folderSegments,
                    "folderURLArray"       => $folderURLArray,

                    "files"                => $slides,
                    'customFiles'          => $customFiles,

                    "lessonHistory"        => $lessonHistory,
                    "slideHistory"         => $slideHistory ?? null,

                    "audioFiles"           => $audioFiles ?? null,
                    
                ])->withHeaders([                  
                    'Accept-Ranges' => 'bytes',                    
                ]); 

    }



    /** 
        @description: get the lesson material slides of the folder 
    */    
    public function getLessonMaterialSlidesCurrentFolder(Request $request)
    {

    
        $scheduleID     = $request->scheduleID;
        $memberID       = $request->memberID;

        /*
            @todo: get the selected member (lesson_id) : if the member has not selected return false
        */
        $material = MemberSelectedLessonSlideMaterial::where('schedule_id', $scheduleID)->where('user_id', $memberID)->first();

        if ($material) {
            
            $folderID       = $material->folder_id;

            


            $folderSegments     = Folder::getURLSegments($folderID, " > ");
            $folderURLArray     = Folder::getURLArray($folderID);
         
            $files              = File::where('folder_id', $folderID)->orderBy('order_id', 'ASC')->get();
            $customFiles        = CustomTutorLessonMaterials::where('lesson_schedule_id', $scheduleID)->where('folder_id', $folderID)->orderBy('order_id', 'ASC')->get(); 
         

            //get Lesson History details
            $lessonHistory = LessonHistory::where('member_id', $memberID)->where('schedule_id', $scheduleID)->where('status', "NEW")->first();
            if ($lessonHistory) {
                //Initialzie Audio Objects
                for($ctr= 0; $ctr <=  $lessonHistory->total_slides ; $ctr ++) {            
                    $audioFiles[$ctr+1] =  [];
                }
            } 


            //@note: lesson slide history
            $lessonSlideHistory = new LessonSlideHistory();
            $slideHistory = $lessonSlideHistory->getAllSlideHistory($scheduleID);


            if ($files) {
                $slides = [];
                foreach ($files as $index => $file) {
                    array_push($slides, url($file->path));
                    //make the index same as the slide number
                    $audioFiles[$index+1] = FileAudio::where('file_id', $file->id)->get(['id', 'file_id', 'path', 'file_name']);
                }                
            } else {            
                $slides = [];
            }


            //seach for files in folder as images
            return Response([
                        "success"              => true,
                        'folderID'             => $folderID, 
                        "folderSegments"       => $folderSegments,
                        "folderURLArray"       => $folderURLArray,

                        "files"                => $slides,
                        'customFiles'          => $customFiles,

                        "lessonHistory"        => $lessonHistory,
                        "slideHistory"         => $slideHistory ?? null,

                        "audioFiles"           => $audioFiles ?? null,
                        
                    ])->withHeaders([                  
                        'Accept-Ranges' => 'bytes',                    
                    ]);  

        } 
    }

    /*
        @description: Get the folder with heirarchy for file manager
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
    
    public function getLessonSelectedPreview(Request $request) 
    {


        if (isset($request->lessonSelectedFolderID)) {

            //selected option id
            $file = File::where('folder_id', $request->lessonSelectedFolderID)
                            ->orderBy('order_id', 'ASC')
                            ->get();

            if ($file) {

                $folder = Folder::where('id', $request->lessonSelectedFolderID)->first();

                return Response()->json([
                    "success"       => true,     
                    "files"         => $file,
                    "folder"        => $folder,
                    "message"       => "Lesson Material has fetched successfully"
                ]);  


            } else {

                return Response()->json([
                    "success"       => false,
                    "message"       => "Files for this folder was not found"
                ]);   
            }
            
        } else {

            return Response()->json([
                "success"       => false,
                "message"       => "Please select a lesson"
            ]); 

        }
    
    }
}
