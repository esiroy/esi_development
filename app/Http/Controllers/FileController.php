<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

use App\Models\File;
use App\Models\Folder;
use Gate;
use Auth;

class FileController extends Controller
{

    /**
     * Display the file on file/$id.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $canView = File::canView($id);

        if ($canView->success) {
            $file = $canView->file;
            $title          = $file->file_name;
            $url            = url($file->path);
            $path           = parse_url($url, PHP_URL_PATH);       // get path from url
            $extension      = pathinfo($path, PATHINFO_EXTENSION); // get ext from path
            $filename       = pathinfo($path, PATHINFO_FILENAME);  // get name from path
            return view('modules.publicfile.show', compact('title', 'url', 'file', 'filename', 'extension'));
        } else {
                
               
            return abort('404', 'The post you are looking for was not found');
           
        }
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {

        abort_if(Gate::denies('filemanager_upload_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->type == "cancel") 
        {
            //search files with 0 file size and delete them including current one
            $removeFiles = File::where('size', 0)->get();

            if ($removeFiles) {

                foreach($removeFiles as $removeFile) {
                    $removeFile->delete();
                }
                
                //Output JSON reply
                return Response()->json([
                    "success"   => true,
                    "message"   => "cancelled file has been removed"
                ]); 

            } else {

                return Response()->json([
                    "success"   => false,
                    "message"   => "cancelled file can not be removed"
                ]);         
            }
        } else {

            //destroy id
            $file = File::find($id);

            if ($file) {
                
                //delete actual file storage 
                Storage::delete("public/uploads/". $file->folder_id ."/". basename($file->path));

                //delete the database
                $file->delete();
                
                //Output JSON reply
                return Response()->json([
                    "success"   => true,
                    "deleted"   => basename($file->path)
                ]);

            } else {
                //Output JSON reply
                return Response()->json([
                    "success"   => false,
                ]); 
    
            }

        }
    }



}
