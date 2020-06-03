<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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

            //create public path -> public/storage/uploads/{folder_id}
            $public_file_path = $originalPath . $request->folder_id . "/". $newFilename;

            // Save to file
            File::create([
                'folder_id' => $request->folder_id,
                'file_name' => $request->file('file')->getClientOriginalName(),
                'path'      => $public_file_path
            ]);
            
            //Output json reply
            return Response()->json([
                "success"   => true,
                'folder_id' => $request->folder_id,
                "file"      => $request->file('file')->getClientOriginalName(),
                "path"      => $path
            ]);
        
        } else {
            return Response()->json([
                "success" => false
            ]);

        }
        

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

    public function show($name)
    {

        $folder = Folder::where('folder_name', $name)->first();
        $files  = Folder::find($folder->id)->files;

        $data = [
            'folder' => $folder,
            'files'  => $files
        ];
        
        return view('modules.uploader.show', $data);
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