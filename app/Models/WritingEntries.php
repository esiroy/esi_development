<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Storage;

class WritingEntries extends Model
{
    public $table = "writing_entries";

    protected $guarded = array('created_at', 'updated_at');

    public $timestamps = true;



    function generateFileAnchorLink($filename) 
    {
        $basename = basename($filename);      
        $fileExtArray = explode(".",  $basename);

        $fileExtension = $fileExtArray[1];
        $fileURL = Storage::url($filename); 

        switch ($fileExtension) {
            case "jpg":              
            case "jpeg":
            case "png":
            case "gif":
                   
                echo "<img src='$fileURL' class='img-fluid'/>";
            break;
            default:
              echo "<a href='$fileURL'>$basename</a>";
        }
    }

}
