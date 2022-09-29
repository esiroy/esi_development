<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WebRTCVideoController extends Controller
{
    public function index() 
    {
        return view('modules.webRTC.index');
    }
}
