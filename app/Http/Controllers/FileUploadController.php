<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;

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

    public function index()
    {
        return view('modules.uploader.index', $this->data);
    }

    public function create() 
    {
        return view('modules.uploader.create');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {


        return view('modules.uploader.show', $this->data);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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
