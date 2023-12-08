<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use App\Models\User;
use App\Models\Folder;
use App\Models\File;
use App\Models\FileAudio;
use App\Models\Permalink;


class FileManagerController extends Controller
{


    public function saveFileNotes(Request $request)
    {

        $file     = File::find($request->id);

        if ($file) {
        
            $updated = $file->update([
                'notes' => $request->notes
            ]);

            if ($updated) {
        
                return Response()->json([
                    "success"   => true,
                    "message"    => "Notes updated"
                ]);
            } else {
            

                return Response()->json([
                    "success"   => false,
                    "message"    => "Notes failed to update"
                ]);
            
            }
        } else {
        
              return Response()->json([
                    "success"   => false,
                    "message"    => "Notes failed to update"
                ]);
        
        }
    }
}
