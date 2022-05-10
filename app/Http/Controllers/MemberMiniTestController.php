<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Models\MiniTestCategory;

class MemberMiniTestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    
    public function index() 
    {

        $category = MiniTestCategory::where('slug', "default")->where('valid', true)->first();


        if ($category) {
        
            return view("minitest/index", ['title'=> $category->name, 'category'=> $category ]);   

        } else {
        
            return abort('404');
        
        } 
    }


    public function show($slug) 
    {

        //@todo:fix slug on add and edit in admin

        $category = MiniTestCategory::where('slug', $slug)->where('valid', true)->first();

        if ($category) {
        
            return view("modules/minitest/show", ['title'=> $category->name, 'category'=> $category ]);   

        } else {
        
            return abort('404');
        
        }       
    
    }
}
