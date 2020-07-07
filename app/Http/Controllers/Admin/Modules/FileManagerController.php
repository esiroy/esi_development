<?php

namespace App\Http\Controllers\Admin\Modules;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

use App\Models\User;
use App\Models\Folder;
use App\Models\File;

use Gate;
use Validator;
use Input;
use DB;

class FileManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        abort_if(Gate::denies('filemanager_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        //@can('filemanager_upload_delete)
        $can_user_share_folder  = (Gate::denies('filemanager_share')) ? 'false' : 'true';
        $can_user_create_folder  = (Gate::denies('filemanager_create')) ? 'false' : 'true';
        $can_user_edit_folder    = (Gate::denies('filemanager_edit')) ? 'false' : 'true';
        $can_user_delete_folder    = (Gate::denies('filemanager_delete')) ? 'false' : 'true';
        $can_user_upload         = (Gate::denies('filemanager_upload')) ? 'false' : 'true';
        $can_user_delete_uploads = (Gate::denies('filemanager_upload_delete')) ? 'false' : 'true';

     
        $all_users = User::get();
        foreach ($all_users as $user) {
            $users[] = [
                'id'    => $user->id,
                'name'  => $user->name,
                'code' => $user->id,
            ];
        }

        $folders = Array();
        $files = Array();
       

        
        return view('admin.modules.filemanager.index', compact(
            'users',
            'folders', 
            'files', 
            'can_user_upload',
            'can_user_delete_uploads', 
            'can_user_create_folder',
            'can_user_share_folder',
            'can_user_edit_folder',
            'can_user_delete_folder'
        ));
    }



    public function dataTableIndex()
    {

        abort_if(Gate::denies('filemanager_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        //@can('filemanager_upload_delete)
        $can_user_delete_uploads = (Gate::denies('filemanager_upload_delete')) ? 'false' : 'true';

        $folders = Folder::all();
        

        return view('admin.modules.filemanager.index-datatables', compact('folders', 'files', 'can_user_delete_uploads'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(Gate::denies('filemanager_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Folder::all()->pluck('id','folder_name', 'folder_description', 'created_at');

        return view('admin.modules.filemanager.create', compact('roles'));
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

        //disallow duplicate folder name
        $validator = Validator::make($request->all(), [
            'folder_name' => 'required|unique:folders|max:191',
            'folder_description' => [
                'max:191'
            ]
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.module.filemanager.create')->withErrors($validator)->withInput();
        }
       
        //Create Folder 
        $folder = Folder::create([
            'folder_name'           => $request['folder_name'],
            'folder_description'    => $request['folder_description']
        ]);

        //return redirect()->route('uploader.index');

        return redirect(route('admin.module.filemanager.show', $folder->id))->with('message', 'Folder has been create successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        abort_if(Gate::denies('filemanager_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        //@can('filemanager_upload_delete)
        $can_user_delete_uploads = (Gate::denies('filemanager_upload_delete')) ? 'false' : 'true';
       
        $folder = Folder::find($id);

        if ($folder) {

            $folders = Folder::get();

            $files  = Folder::find($folder->id)->files;
            
            return view('admin.modules.filemanager.show', compact('folders', 'folder', 'files', 'can_user_delete_uploads'));

        } else {

            return redirect( route('admin.module.filemanager.index') )->with('error_message', 'Folder cant be found, it may have been deleted already.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        abort_if(Gate::denies('filemanager_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $folder = Folder::find($id);

        if ($folder) {

            $folders = Folder::get();
            $files  = Folder::find($folder->id)->files;

            return view('admin.modules.filemanager.edit', compact('folders', 'folder', 'files'));

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
    public function update(Request $request, $id)
    {
        abort_if(Gate::denies('filemanager_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

 
        $folder = Folder::find($id);

        //disallow duplicate folder name
        $validator = Validator::make($request->all(), 
        [
            'folder_name' => [
                'required',
                'max:191',
                Rule::unique('folders')->ignore($folder->id),
            ],
            'folder_description' => [
                'max:191'
            ]
        ]);

        if ($validator->fails()) {
            return redirect( route('admin.module.filemanager.edit', $name))
                        ->withErrors($validator)
                        ->withInput();
        }


        //Update Folder 
        $folder->update([
            'folder_name'           => $request['folder_name'],
            'folder_description'    => $request['folder_description']
        ]);


        return redirect( route('admin.module.filemanager.index', $id) )->with('message', 'Folder has been updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        abort_if(Gate::denies('filemanager_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $folder = Folder::find($id);
        
        if ($folder) {


            Storage::deleteDirectory("public/uploads/". $folder->id);

            $folder->delete();

            return redirect( route('admin.module.filemanager.index') )->with('message', 'Folder has been deleted successfully!');
            
        } else {

            return redirect( route('admin.module.filemanager.index') )->with('error_message', 'Folder cant be found, it may have been deleted already.');
        }

    }
}