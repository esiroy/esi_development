<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;
use Input;
use Image;

use App\Models\User;
use App\Models\Folder;


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

            $originalPath = 'storage/uploads/';
            $newFilename = $originalPath.time().$files->getClientOriginalName();
            
            // for save original image
            $path = $request->file('file')->store('file');
        }
        
        return Response()->json([
            "success" => true,
            "file"  => $request->file('file')->getClientOriginalName(),
            "path"  => $path
           
        ]);
    }

    //Folders Controllers
    public function index()
    {
        return view('modules.uploader.index', $this->data);
    }

    public function create() 
    {
        return view('modules.uploader.create');
    }

    public function show($id)
    {
        return view('modules.uploader.show', $this->data);
    }

    //store new folder
    public function store(Request $request)
    {
        //Disallows duplicate folder name
        $validator = Validator::make($request->all(), [
            'folder_name' => 'required|unique:folders|max:255',
           
        ]);

        if ($validator->fails()) {
            return redirect('uploader/create')
                        ->withErrors($validator)
                        ->withInput();
        }
       
        //Create Folder 
         Folder::create([
            'folder_name' => $request['folder_name'],
            'folder_description' => $request['folder_description']
        ]);

        return redirect()->route('uploader.index');
    }




}
