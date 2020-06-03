<?php

/* This Controller will show all the files available for the speficed folder */

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Folder;
use App\Models\File;

class FolderController extends Controller
{
    //Public view
    public function show($name)
    {
        $folder = Folder::where('folder_name', $name)->first();

        if ($folder) {

            $files = Folder::find($folder->id)->files;

            $data = [
                'folder' => $folder,
                'files' => $files
            ];

            return view('modules/folder/show', $data);


        } else {

            abort(404);
        }

    }
}
