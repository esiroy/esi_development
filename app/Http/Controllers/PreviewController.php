<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Image;

class PreviewController extends Controller
{
    public function show($filePath, Request $request ) 
    {   

        return Image::make($request->get('url'))->resize(null, 120, function ($constraint) { $constraint->aspectRatio(); })->response('png');
    }

}
