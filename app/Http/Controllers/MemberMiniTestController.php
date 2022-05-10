<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MemberMiniTestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    
    public function index() 
    {
        return view("dummy/index", ['title'=> "TEST"]);
    }    
}
