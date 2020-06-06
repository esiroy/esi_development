<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\File;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {

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
