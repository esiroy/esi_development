<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

use Gate;
use Validator;
use Input;

use App\Models\User;
use App\Models\Folder;
use App\Models\File;

class FolderCreatorController extends Controller
{

    public $parent_id;

    public function __construct()
    {
        $this->middleware('auth');
        $this->data = [
            'folders' => Folder::get()
        ];

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     /*
    public function index()
    {
        abort_if(Gate::denies('filemanager_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('modules.uploader.indexv1', $this->data);
    }
    */


    public function index()
    {
        abort_if(Gate::denies('filemanager_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        //@can('filemanager_upload_delete)
        $can_user_create_folder  = (Gate::denies('filemanager_create')) ? 'false' : 'true';
        $can_user_edit_folder    = (Gate::denies('filemanager_edit')) ? 'false' : 'true';
        $can_user_delete_folder    = (Gate::denies('filemanager_delete')) ? 'false' : 'true';
        $can_user_upload         = (Gate::denies('filemanager_upload')) ? 'false' : 'true';
        $can_user_delete_uploads = (Gate::denies('filemanager_upload_delete')) ? 'false' : 'true';

        //$folders = (Folder::getFoldersRecursively());
        $folders = Array();
        $files = Array();
        
        
        
        return view('modules.uploader.index', compact(
            'folders', 
            'files', 
            'can_user_upload',
            'can_user_delete_uploads', 
            'can_user_create_folder',
            'can_user_edit_folder',
            'can_user_delete_folder'
        ));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() 
    {
        abort_if(Gate::denies('filemanager_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('modules.uploader.create', $this->data);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        abort_if(Gate::denies('filemanager_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $validator = Validator::make($request->all(), 
        [
            'folder_name' => [
                'required',
                'max:191',
                Rule::unique('folders')->where(function ($query) {
                    return $query->where('parent_id', 0);
                })
            ],
            'folder_description' => [
                'max:191'
            ]
        ]);


        if ($validator->fails()) {
            return redirect('uploader/create')->withErrors($validator)->withInput();
        }
       
        //Create Folder 
        $folder = Folder::where('parent_id', 0)->get();
        $next_order_id =  ($folder->max('order_id')) ? $folder->max('order_id') + 1 : 1;

        Folder::create([
            'slug'                  => Str::slug($request['folder_name'], '-'),
            'folder_name'           => $request['folder_name'],
            'folder_description'    => $request['folder_description'],
            'order_id'              => $next_order_id 
        ]);

        //return redirect()->route('uploader.index');

        return redirect('uploader/'. $request->folder_name)->with('message', 'Folder has been create successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($name)
    {
        abort_if(Gate::denies('filemanager_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // Can user Delete upload variable is sent to vuejs component
        $can_user_delete_uploads = (Gate::denies('filemanager_upload_delete')) ? 'false' : 'true';   
        $folder = Folder::where('folder_name', $name)->first();

        if ($folder) {
            $files  = Folder::find($folder->id)->files;
            $folders = Folder::get();

            return view('modules.uploader.show', compact('folders', 'folder', 'files', 'can_user_delete_uploads'));
        } else {

            return redirect( route('uploader.index') )->with('error_message', 'Folder cant be found, it may have been deleted already.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($name)
    {
        abort_if(Gate::denies('filemanager_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $name)
    {
        abort_if(Gate::denies('filemanager_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $folder = Folder::where('folder_name', $name)->first();

        $this->parent_id = $folder->parent_id;

        $validator = Validator::make($request->all(), 
        [
            'folder_name' => [
                'required',
                'max:191',
                Rule::unique('folders')->where(function ($query) {
                    return $query->where('parent_id', $this->parent_id);
                })->ignore($folder->id)
            ],
            'folder_description' => [
                'max:191'
            ]
        ]);

        if ($validator->fails()) {
            return redirect( route('uploader.edit', $name))
                        ->withErrors($validator)
                        ->withInput();
        }


        //Update Folder 
        $folder->update([
            'slug'                  => Str::slug($request['folder_name'], '-'),
            'folder_name'           => $request['folder_name'],
            'folder_description'    => $request['folder_description']
        ]);


        return redirect('uploader/'. $request->folder_name)->with('message', 'Folder has been updated successfully!');

    }


    public function destroy($id)
    {
        abort_if(Gate::denies('filemanager_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $folder = Folder::find($id);
        
        if ($folder) {


            Storage::deleteDirectory("public/uploads/". $folder->id);

            $folder->delete();

            return redirect( route('uploader.index') )->with('message', 'Folder has been deleted successfully!');

           
            
        } else {
            return redirect( route('uploader.index') )->with('error_message', 'Folder cant be found, it may have been deleted already.');
        }

        
    }


}
