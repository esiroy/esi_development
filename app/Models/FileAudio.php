<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FileAudio extends Model
{
    protected $guarded = array('created_at', 'updated_at');
    

    
    function getAudioList($folderID) 
    {
        $files      = $file->where('files.folder_id', $folderID)->orderBy('files.order_id', 'ASC')->get();
    }
}
