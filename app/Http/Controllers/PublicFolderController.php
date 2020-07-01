<?php

/* This Controller will show all the files available for the speficed folder */

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Folder;
use App\Models\File;
use App\Models\Permalink;
use Gate;

class PublicFolderController extends Controller
{
    public function index()
    { 
        return view('welcome');

    }

    //Public view
    public function show($name)
    {

        //@can('filemanager_upload_delete)
        $can_user_create_folder  = (Gate::denies('filemanager_create')) ? 'false' : 'true';
        $can_user_edit_folder    = (Gate::denies('filemanager_edit')) ? 'false' : 'true';
        $can_user_delete_folder    = (Gate::denies('filemanager_delete')) ? 'false' : 'true';
        $can_user_upload         = (Gate::denies('filemanager_upload')) ? 'false' : 'true';
        $can_user_delete_uploads = (Gate::denies('filemanager_upload_delete')) ? 'false' : 'true';


        $permalink =  Permalink::where('permalink', $name)->first();

        //$folders = (Folder::getFoldersRecursively());
        $folders = Array();
        $files = Array();

    

        if (isset($permalink->folder)) 
        {
            $files = Folder::find($permalink->folder->id)->files;

            $data = [
                'folder' => $permalink->folder,
                'files' => $files,
                'folders' => $folders,
                'can_user_upload' => 'false',
                'can_user_delete_uploads' => 'false', 
                'can_user_create_folder' => 'false',
                'can_user_edit_folder' => 'false', 
                'can_user_delete_folder' =>  'false'
            ];

            return view('modules/publicfolder/show', $data);
        } else {

            abort(404);
        }
    }
}
