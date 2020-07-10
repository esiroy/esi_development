<?php

/* This Controller will show all the files available for the speficed folder */

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Folder;
use App\Models\File;
use App\Models\Permalink;
use Gate;
use Auth;

class PublicFolderController extends Controller
{
    public function index()
    { 
        return view('welcome');

    }

    //Public view
    public function show($name)
    {
        $public_viewer_id = (Auth::check()) ? Auth::user()->id : 'null';

        $permalink =  Permalink::where('permalink', $name)->first();

        if  (!isset($permalink->folder)) 
        {
            abort(404);
        }
        else if (Auth::check())
        {
            if (Gate::forUser(Auth::user())->denies('permission', "filemanager_admin")) 
            {
                if ($permalink->folder->privacy == 'private') 
                {
                    if (Folder::hasPermission($public_viewer_id, $permalink->folder->id) == false) {
                        
                        $data = [
                            'success'   => false,
                            'message'   => "Please ask for permission from the folder owner to view this folder"
                        ];

                        return view('modules/publicfolder/message', $data);
                    }
                } else {
                    //public folder
                }
            } else {
                //@user can do it all, it has filemanager admin overrides
            }
    
        } else {
            if ($permalink->folder->privacy == 'private') 
            {
                $data = [
                    'success'   => false,
                    'message'   => "You must be logged in to view this folder"
                ];

                return view('modules/publicfolder/message', $data);
            }
        }

        //$folders = (Folder::getFoldersRecursively());
        $folders = Array();
        $files = Array();



        /** Enable below if you want activate file permission on public page */
        /*
        $can_user_share_uploads     = (Gate::denies('filemanager_upload_share')) ? 'false' : 'true';
        $can_user_create_folder  = (Gate::denies('filemanager_create')) ? 'false' : 'true';
        $can_user_edit_folder    = (Gate::denies('filemanager_edit')) ? 'false' : 'true';
        $can_user_delete_folder    = (Gate::denies('filemanager_delete')) ? 'false' : 'true';
        $can_user_upload         = (Gate::denies('filemanager_upload')) ? 'false' : 'true';
        $can_user_delete_uploads = (Gate::denies('filemanager_upload_delete')) ? 'false' : 'true';
        */


        if (isset($permalink->folder)) 
        {
            $files = Folder::find($permalink->folder->id)->files;

            $data = [
                'public'                    => 'true',
                'public_viewer_id'          => $public_viewer_id,
                'title'                     => $permalink->folder->folder_name,
                'folder'                    => $permalink->folder,
                'files'                     => $files,
                'folders'                   => $folders,
                'can_user_share_uploads'    => 'false',
                'can_user_upload'           => 'false',
                'can_user_delete_uploads'   => 'false', 
                'can_user_create_folder'    => 'false',
                'can_user_edit_folder'      => 'false', 
                'can_user_delete_folder'    =>  'false'
            ];

            return view('modules/publicfolder/show', $data);

        } else {

            abort(404);
        }


    }
}
