<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File; 


class UploadFile extends Model
{

    public function test() {
        echo "TEST";
    }

    public function uploadFile($storagePath, $file) {

        $newFilename = time() . "_" . preg_replace('/\s+/', '_', $file->getClientOriginalName());
        $newFilename = mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $newFilename);
        // Remove any runs of periods (thanks falstro!)
        $newFilename = mb_ereg_replace("([\.]{2,})", '', $newFilename);

        //check if the filesize is not 0 / or cancelled
        if ( $file->getSize() > 0) 
        {
            //save in storage -> storage/public/course_category_images/
            $path = $file->storeAs(
                //file path
                $storagePath, $newFilename
            );
            //create public path -> public/storage/course_category_images/{folder_id}
            $public_file_path = $storagePath . $newFilename;

        } else {
            $public_file_path = null;
        }

        return $public_file_path;
    }

    public function deleteUploadedFile($storagePath, $file) {
        $image_path = storage_path('app/'. $storagePath . $file);
        if (File::exists($image_path)) {
            File::delete($image_path);
            return true;
        } else {
            return false;
        }
    }
}