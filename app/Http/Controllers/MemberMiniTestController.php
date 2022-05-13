<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\MiniTestCategoryType;
use App\Models\MiniTestCategory;

class MemberMiniTestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    
    public function index(MiniTestCategoryType $miniTestCategoryType) 
    {

        $parents = $miniTestCategoryType->getCategoryTypeParentLinks();
        return view("modules.minitest.index", ['parents'=> $parents ]); 
    }


    public function show($slug, $categoryType = null) 
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
