<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Folder;
use App\Models\File;

class LessonFolderController extends Controller
{

    public function getLessonFolders(Request $request, Folder $folder, File $file)
    {

        $folderCategories = [];
        $files          = [];

        if (isset($request->folderID)) {

            $folderID = $request->folderID;

            //parent folder details
            $currentFolder = $folder->where('id', $folderID)->where('privacy', 'public')->first();

            if (!$currentFolder) {
            
                return Response()->json([
                    "success"               => true,
                    "message"               => "Folder was not found, please try again"
                ]);   

            }

            //get the sub folders 
            $folders = $folder->where('parent_id', $folderID)->where('privacy', 'public')->orderBy('order_id', 'ASC')->get();

            $files = $file->where('folder_id', $folderID)->orderBy('order_id', 'ASC')->get(); 


            foreach ($folders as $folder) {  
                array_push($folderCategories, $folder);
            }


            return Response()->json([
                "success"               => true,
                "currentFolderID"       => $folderID,
                "currentFolder"         => $currentFolder,
                "parentID"              => $currentFolder->parent_id,
                "folderCategories"      => $folderCategories,
                'files'                 => $files
            ]);   


        } else {
        

            //@todo: get the parent folder (id = 0)

            $folderID = 0;


            $folders = $folder->where('parent_id', $folderID)->where('privacy', 'public')->orderBy('order_id', 'ASC')->get();

            $files = $file->where('folder_id', $folderID)->get(); 

            foreach ($folders as $folder) 
            {
                array_push($folderCategories, $folder);
            }


            return Response()->json([
                "success"               => true,
                "currentFolderID"       => null,
                "currentFolder"         => null,                
                "parentID"              => null,
                "folderCategories"      => $folderCategories,
                'files'                 => $files
            ]);  

        }
    }

    public function searchFolders(Request $request, Folder $folder, File $file) {
    
    
    }
   
}
