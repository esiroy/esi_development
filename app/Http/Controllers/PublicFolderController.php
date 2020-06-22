<?php

/* This Controller will show all the files available for the speficed folder */

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Folder;
use App\Models\File;
use App\Models\Permalink;

class PublicFolderController extends Controller
{
    public function index()
    { 
        return view('welcome');

    }

    //Public view
    public function show($name)
    {
       $permalink =  Permalink::where('permalink', $name)->first();

       if (isset($permalink->folder)) {
            $files = Folder::find($permalink->folder->id)->files;
            $data = [
                'folder' => $permalink->folder,
                'files' => $files
            ];

            return view('modules/publicfolder/show', $data);
        } else {

            abort(404);
        }
    }
}
