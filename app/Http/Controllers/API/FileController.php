<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

use App\Models\File;


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
