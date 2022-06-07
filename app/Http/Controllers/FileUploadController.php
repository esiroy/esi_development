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
use App\Models\ChatSupportHistory;

//use App\Models\Folder;
//use App\Models\File;

class FileUploadController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');

        /*
        $this->data = [
            'folders' => Folder::get()
        ];
        */

    }

    public function upload(Request $request, ChatSupportHistory $chatSupportHistory) 
    {

        //abort_if(Gate::denies('filemanager_upload'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($files = $request->file('file')) {

            //file path
            $originalPath = 'storage/uploads/chatsupport';

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
                    'public/uploads/chatsupport/'.$request->folder_id , $newFilename
                );

                //create public path -> public/storage/uploads/{folder_id}
                $public_file_path = $originalPath . $request->folder_id . "/". $newFilename;

                // Save to file
                /*
                $file = File::create([
                    'user_id'       => Auth::user()->id,
                    'folder_id'     => $request->folder_id,
                    'file_name'     => $request->file('file')->getClientOriginalName(), //original filename
                    'upload_name'   => $request->file('file')->getFileName(), //generated filename
                    'size'          => $request->file('file')->getSize(),
                    'path'          => $public_file_path,
                ]);
                */
                $url = url(Storage::url($path));                
                $ext = pathinfo($path, PATHINFO_EXTENSION);

                if ($ext == 'jpg' || $ext == 'png' || $ext == 'gif' || $ext == 'jpeg') {
                    $file = "<a class='img_preview' target='_blank' href='$url'><img src='$url' class='img_preview'></a>";
                } else {
                    $file = "<a class='img_preview' target='_blank' href='$url'><i class='fas fa-file-pdf custom-pdf'></i></a> ";
                    $file .= "<div>". $request->file('file')->getClientOriginalName() ."</div>";
                }
                

                $data = [
                    'sender_id'     =>  Auth::user()->id,
                    'recipient_id'  => $request->current_chatbox_userid,
                    'message'       => $file,
                    'message_type'  => $request->message_type,
                    'is_read'       => false,
                    'valid'         => true,
                ];      
        
                $chatSupportItem = $chatSupportHistory->create($data);

                $recipient = User::find($request->current_chatbox_userid);

                //Output JSON reply
                return Response()->json([
                    "success"       => true,                    
                    'user_id'       => Auth::user()->id,
                    'recipient_id'  => $request->current_chatbox_userid,
                    'recipient_username'      => $recipient->username,
                    //'id'            => $file->id,
                    //'folder_id'     => $request->folder_id,
                    "file"          => $request->file('file')->getClientOriginalName(), //original filename
                    "upload_name"   => $request->file('file')->getFileName(),  //generated filename
                    'size'          => $request->file('file')->getSize(),
                    "path"          => $path,
                    "image"         => $file,
                    "url"           => $url,
                    "ext"           => $ext,
                    'current_chatbox_userid' =>  $request->current_chatbox_userid,
                    "owner"         => Auth::user()
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