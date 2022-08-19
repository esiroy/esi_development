<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth, DB;

use App\Models\Folder;


class LessonSlideMaterials extends Controller
{

    public function get(Request $request)
    {
        $lessonID = $request->lesson_id;
    }

    public function getLessonSlideMaterialList(Request $request) 
    {
        //$request
        $folders = (Folder::getPrivateFolders());

    

        return Response()->json([
            "success"               => true,
            "folders"               =>  $folders
        ]);
    
    }

}
