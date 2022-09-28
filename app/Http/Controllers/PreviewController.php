<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Image;

class PreviewController extends Controller
{
    public function show(Request $request ) 
    {   
    
        $fileExt =   explode(".", $request->get('url'));

        $ctr = count($fileExt) - 1;

        if ($fileExt[$ctr] == "pdf" ) 
        {
             return Image::make('https://alltopstartups.com/wp-content/uploads/2020/01/What-Is-a-PDF-File-And-What-Are-PDFs-For.png')->resize(null, 120, function ($constraint) { $constraint->aspectRatio(); })->response();  

        } else if ($fileExt[$ctr] == "mp3" ) {

            return Image::make('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT-7oY6pvjnbuVD_03aCdjJJtTn7o5PxfOFmHe8cHHf7g')->resize(null, 120, function ($constraint) { $constraint->aspectRatio(); })->response();  

        } else {
            return Image::make($request->get('url'))->resize(null, 120, function ($constraint) { $constraint->aspectRatio(); })->response();        
        }

        
    }

}
