<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DebugController extends Controller
{
    public function index(Request $request)
    {

        return view('debug/debugindex');
    }
}