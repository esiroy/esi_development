<?php

namespace App\Http\Controllers\Admin\Modules;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WritingController extends Controller
{
    //

    public function indeX() 
    {
        return view('admin.modules.writing.index');
    }
}
