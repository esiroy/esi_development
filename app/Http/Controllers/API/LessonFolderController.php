<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;
use App\Models\Folder;
use App\Models\File;

class LessonFolderController extends Controller
{

    public function getLessonList(Request $request, Folder $folder, File $file) {

        $itemsPerPage = 10;            

        if (isset($request->folderID)) 
        {

            if(!isset($request->page)) {
                $page = 1;
            } else {
                $page = $request->page;
            }


            $folderID = $request->folderID;      

            //parent folder details
            $currentFolder = $folder->where('id', $folderID)->where('privacy', 'public')->first();

            if (!$currentFolder) {            
                return Response()->json([
                    "success"               => true,
                    "message"               => "Folder was not found, please try again"
                ]);
            }
           
            $lessons = $folder->getFolderLessons($folderID, $page, $itemsPerPage);
        }

        return Response()->json([
            "success"               => true,
            "currentFolderID"       => $folderID,
            "currentFolder"         => $currentFolder,
            "parentID"              => $currentFolder->parent_id,
            "lessons"               => $lessons
        ]);   

    }



    public function getLessonFolders(Request $request, Folder $folder, File $file)
    {       

        $itemsPerPage = 10;

        $folderCategories   = [];
        $lessons            = [];
        $files              = [];

        if(!isset($request->page)) {
            $page = 1;
        } else {
            $page = $request->page;
        }
                

        if (isset($request->folderID)) 
        {
            $folderID = $request->folderID;

            //parent folder details
            $currentFolder = $folder->where('id', $folderID)->where('privacy', 'public')->orderBy('order_id', 'ASC')->first();

            if (!$currentFolder) {            
                return Response()->json([
                    "success"               => true,
                    "message"               => "Folder was not found, please try again"
                ]);
            }


            $folderType     = "subFolder";
            $folders        = $folder->where('parent_id', $folderID)->where('privacy', 'public')->orderBy('order_id', 'ASC')->get();
            $lessons        = $folder->getFolderLessons($folderID, $page, "*");
            $files          = [];

            $folderCategories = []; 
            
            foreach ($folders as $index => $folder) {
            
                $parentFolders = $folder->getParentFolders($folder->id);

                $isThumbExist = (Storage::disk('thumbnails')->exists($folder->thumb_file_name)) ? true : false;
                $folders[$index]['isThumbExist'] = $isThumbExist;       

                $lessonCounter = $folder->where('parent_id', $folder->id)->where('privacy', 'public')->orderBy('order_id', 'ASC')->count();

                if ($lessonCounter >= 1) { 

                    $folders[$index]['formatted_folder_name']     = ucwords($folder->folder_name); 
                    $folders[$index]['subcategoryCounter']        = $lessonCounter;
                    $folders[$index]['parentFolders']             = $parentFolders;

                    //add to folder categories list
                    $folderCategories[] = $folder;



                } else {

                    //@note: we will not push the folder to folder categories since the lesson counter is 0
                    $folders[$index]['subcategoryCounter'] = $lessonCounter;
                    $folders[$index]['parentFolders']             = $parentFolders;

                }
            }

            return Response()->json([
                "success"               => true,
                "folderType"            => $folderType,
                "currentFolderID"       => $folderID,
                
                "currentFolder"         => $currentFolder,
                "urlTitles"             => $folder->getURLTitles( $folderID),
                "parentID"              => $currentFolder->parent_id,
                "folderCategories"      => $folderCategories,
                "lessons"               => $lessons,
                "lesson_rows"           => count($lessons),
                'files'                 => $files
            ]);   


        } else {       

            $folderType     = "parent";
            $folderID       = 0;
            $folders        = $folder->where('parent_id', $folderID)->where('privacy', 'public')->orderBy('order_id', 'ASC')->get();
            $files          = $file->where('folder_id', $folderID)->get(); 


            
            foreach ($folders as $index => $folder) {
            
                $parentFolders = $folder->getParentFolders($folder->id);

                $isThumbExist = (Storage::disk('thumbnails')->exists($folder->thumb_file_name)) ? true : false;

                $folders[$index]['formatted_folder_name']     = ucwords($folder->folder_name); 
                $folders[$index]['isThumbExist'] = $isThumbExist;    
                   
                $folders[$index]['parentFolders']  = $parentFolders;       

                $lessonCounter = $folder->where('parent_id', $folder->id)->where('privacy', 'public')->orderBy('order_id', 'ASC')->count();
                if ($lessonCounter >= 1) {
                    //array_push($folderCategories, $folder);
                    $folders[$index]['subcategoryCounter'] = $lessonCounter; 
                } else {
                    //array_push($lessons, $folder);
                    $folders[$index]['subcategoryCounter'] = $lessonCounter;
                }
            }


            return Response()->json([
                "success"               => true,
                "folderType"           => $folderType,
                "currentFolderID"       => null,
                "currentFolder"         => null,                
                "parentID"              => null,
                "folderCategories"      => $folders,
                "lessons"               => $lessons,
                "lesson_rows"           => 0,
                'files'                 => $files
            ]);  

        }
    }




    public function getLessonImages(Request $request, File $file, Folder $folder)
    {

        $folderID = $request->folderID;
        
        if (!isset($folderID)) {

            return Response()->json([
                "success"       => false,
                "files"         => []
            ]);
        }

        $isBook = $folder->where('id', $folderID)->select('is_book')->first()->is_book;

        $files = $file->where('folder_id', $folderID)->get(['file_name', 'path']);

        return Response()->json([
            "success"       => true,
            "is_book"        => $isBook,
            "files"         => $files,
            "message"       => "Test",            
        ]);  
        
    }


    public function searchFolders(Request $request, Folder $folder, File $file) {

        if ($request->searchKeyword) {
        
            $keyword = $request->searchKeyword;
        
            $folders = $folder
                ->select('id', 'folder_name', 'folder_description', 'order_id')
                ->selectRaw('UCASE(folder_name) as formatted_folder_name') // Use UCASE to format folder_name with ucfirst
                ->where('folder_name', 'LIKE', '%'.$keyword.'%')
                ->orWhere('folder_description', 'LIKE', '%'.$keyword.'%')
                ->orWhere('folder_name', $keyword)
                ->orWhere('folder_description', $keyword)
                ->orderBy('order_id', 'ASC')->get();



            if ($folders) {

                foreach ($folders as $index => $folder) {
                    $parentFolders = $folder->getParentFolders($folder->id);
                    $folders[$index]['parentFolders'] = $parentFolders;

                    $lessonCounter = $folder->where('parent_id', $folder->id)->where('privacy', 'public')->orderBy('order_id', 'ASC')->count();
                    if ($lessonCounter >= 1) {
                        //array_push($folderCategories, $folder);
                        $folders[$index]['subcategoryCounter'] = $lessonCounter; 
                    } else {
                        //array_push($lessons, $folder);
                        $folders[$index]['subcategoryCounter'] = $lessonCounter;
                    }                    
                }
                         

                return Response()->json([
                    "success"       => true,
                    "folders"       => $folders,            
                ]); 

            } else {
                return Response()->json([
                    "success"       => false,
                    "folders"       => [],
                    "message"       => "No Result Found",            
                ]);   
            }

        } else {
        
            return Response()->json([
                "success"       => false,
                "folders"       => [],
                "message"       => "No keywords supplied",            
            ]); 
        }
    }
   
    public function viewSearchFolder(Request $request, Folder $folder) {
    
        $ids = $folder->getURLIDNamePair($request->id);
    
    }
}
