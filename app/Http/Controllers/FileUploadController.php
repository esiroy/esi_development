<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use Validator;
use Input;

use App\Models\User;
use App\Models\Folder;
use App\Models\File;

class FileUploadController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');

        $this->data = [
            'folders' => Folder::get()
        ];

    }

    public function upload(Request $request) {

        if ($files = $request->file('file')) {

            //file path
            $originalPath = 'storage/uploads/';

            $newFilename = time()."_". preg_replace('/\s+/', '_', $files->getClientOriginalName());

            $newFilename = mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $newFilename);
            
            // Remove any runs of periods (thanks falstro!)
            $newFilename = mb_ereg_replace("([\.]{2,})", '', $newFilename);

            
            // for save original image
            //$path = $request->file('file')->store('public/uploads');

            //save in storage -> storage/public/uploads/
            $path = $request->file('file')->storeAs(
                'public/uploads/'.$request->folder_id , $newFilename
            );


            if ($path) {
                //create public path -> public/storage/uploads/{folder_id}
                $public_file_path = $originalPath . $request->folder_id . "/". $newFilename;

                // Save to file
                $file = File::create([
                    'folder_id' => $request->folder_id,
                    'file_name' => $request->file('file')->getClientOriginalName(),
                    'size'      => $request->file('file')->getSize(),
                    'path'      => $public_file_path
                ]);
                
                //Output JSON reply
                return Response()->json([
                    "success"   => true,
                    'id'        => $file->id,
                    'folder_id' => $request->folder_id,
                    "file"      => $request->file('file')->getClientOriginalName(),
                    'size'      => $request->file('file')->getSize(),
                    "path"      => $path
                ]);

            } else {

                return Response()->json([
                    "success"   => false,
                    "message"   => "File Aborted or cancelled"
                ]);
            }
      
        
        } else {
            return Response()->json([
                "success" => false
            ]);

        }
    }


    


}