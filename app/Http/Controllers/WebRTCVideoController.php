<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WebRTCVideoController extends Controller
{
    public function index(Request $request) 
    {

        $roomID = $request->get('roomid');

        return view('modules.webRTC.index', compact('roomID'));
    }
}
