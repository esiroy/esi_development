<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\File;
use DB, Str;

class FileController extends Controller
{
    public function saveFileOrder(Request $request) 
    {

        $files = $request->get('files');

        $reorder = File::reorder($files);

        //reorder has false (error resposse)
        if ($reorder->response == false) 
        {

            return Response()->json([
                "success"       => false,
                "message"       => $reorder->message 
            ]);        

        } else {

            return Response()->json([
                "success"       => true,
                "message"       => $reorder->message 
            ]);      
        }

    }
}
