<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

use Validator;
use Input;

use App\Models\User;
use App\Models\Folder;
use App\Models\File;

class FolderCreatorController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->data = [
            'folders' => Folder::get()
        ];

    }

    public function index()
    {
        return view('modules.uploader.index', $this->data);
    }

    public function create() 
    {
        return view('modules.uploader.create', $this->data);
    }


    public function edit($name)
    {
        $folder = Folder::where('folder_name', $name)->first();

        if ($folder) {

            $files  = Folder::find($folder->id)->files;

            $data = [
                'folders' => Folder::get(),
                'folder' => $folder,
                'files'  => $files
            ];
    
            return view('modules.uploader.edit', $data);

        } else {

            return redirect( route('uploader.index') )->with('error_message', 'Folder cant be found, it may have been deleted already.');

        }


    }



    public function show($name)
    {
        $folder = Folder::where('folder_name', $name)->first();

        if ($folder) {
            $files  = Folder::find($folder->id)->files;

            $data = [
                'folders' => Folder::get(),
                'folder' => $folder,
                'files'  => $files
            ];
            
            return view('modules.uploader.show', $data);
        } else {

            return redirect( route('uploader.index') )->with('error_message', 'Folder cant be found, it may have been deleted already.');
        }
    }

    //store new folder
    public function store(Request $request)
    {
        //disallow duplicate folder name
        $validator = Validator::make($request->all(), [
            'folder_name' => 'required|unique:folders|max:255',
            'folder_description' => [
                'max:255'
            ]
        ]);

        if ($validator->fails()) {
            return redirect('uploader/create')
                        ->withErrors($validator)
                        ->withInput();
        }
       
        //Create Folder 
        Folder::create([
            'folder_name'           => $request['folder_name'],
            'folder_description'    => $request['folder_description']
        ]);

        //return redirect()->route('uploader.index');

        return redirect('uploader/'. $request->folder_name)->with('message', 'Folder has been create successfully!');
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $name)
    {

        $folder = Folder::where('folder_name', $name)->first();

        //disallow duplicate folder name
        $validator = Validator::make($request->all(), 
        [
            'folder_name' => [
                'required',
                'max:255',
                Rule::unique('folders')->ignore($folder->id),
            ],
            'folder_description' => [
                'max:255'
            ]

        ]);

        if ($validator->fails()) {
            return redirect( route('uploader.edit', $name))
                        ->withErrors($validator)
                        ->withInput();
        }


        //Update Folder 
        $folder->update([
            'folder_name'           => $request['folder_name'],
            'folder_description'    => $request['folder_description']
        ]);


        return redirect('uploader/'. $request->folder_name)->with('message', 'Folder has been updated successfully!');

    }


    public function destroy($id)
    {
        $folder = Folder::find($id);
        
        if ($folder) {


            Storage::deleteDirectory("public/uploads/". $folder->id);

            $folder->delete();

            return redirect( route('uploader.index') )->with('message', 'Folder has been deleted successfully!');

            //@todo: delete folder files
            
        } else {
            return redirect( route('uploader.index') )->with('error_message', 'Folder cant be found, it may have been deleted already.');
        }

        
    }


}