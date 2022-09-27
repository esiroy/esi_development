<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Storage;

use Auth;
use Validator;
use Input;
use Gate;

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

    public function upload(Request $request) 
    {
        abort_if(Gate::denies('filemanager_upload'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($files = $request->file('file')) {

            //file path
            $originalPath = 'storage/uploads/';

            $newFilename = time()."_". preg_replace('/\s+/', '_', $files->getClientOriginalName());

            $newFilename = mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $newFilename);
            
            // Remove any runs of periods (thanks falstro!)
            $newFilename = mb_ereg_replace("([\.]{2,})", '', $newFilename);

            //check if the filesize is not 0 / or cancelled
            if ($request->file('file')->getSize() > 0) {

                // for save original image name
                //$path = $request->file('file')->store('public/uploads');

                //save in storage -> storage/public/uploads/
                $path = $request->file('file')->storeAs(
                    'public/uploads/'.$request->folder_id , $newFilename
                );

                //create public path -> public/storage/uploads/{folder_id}
                $public_file_path = $originalPath . $request->folder_id . "/". $newFilename;


                //get the last order-id of the folder
                $latestAlbum = File::where('folder_id', $request->folder_id)->latest()->first();

                $nextOrderID = ($latestAlbum)? $latestAlbum->order_id + 1 : 1;
                    

                

                // Save to file
                $file = File::create([
                    'user_id'       => Auth::user()->id,
                    'folder_id'     => $request->folder_id,
                    'file_name'     => $request->file('file')->getClientOriginalName(), //original filename
                    'upload_name'   => $request->file('file')->getFileName(), //generated filename
                    'size'          => $request->file('file')->getSize(),
                    'path'          => $public_file_path,
                    'order_id'      => $nextOrderID,
                ]);

                //Output JSON reply
                return Response()->json([
                    "success"       => true,
                    'id'            => $file->id,
                    'user_id'       => Auth::user()->id,
                    'folder_id'     => $request->folder_id,
                    "file"          => $request->file('file')->getClientOriginalName(), //original filename
                    "upload_name"   => $request->file('file')->getFileName(),  //generated filename
                    'size'          => $request->file('file')->getSize(),
                    "path"          => $path,
                    //"owner"         => Auth::user()
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