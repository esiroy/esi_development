<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    
    
    //static pages
    public function stageLevel() {
        return view('pages/static/stagelevel');
    }
}
