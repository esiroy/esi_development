<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\MiniTestCategoryType;
use App\Models\MiniTestCategory;
use App\Models\User;
use Auth, Gate;


class MemberMiniTestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware('redirectAdmin');
    }

    
    public function index(MiniTestCategoryType $miniTestCategoryType) 
    {

        $parents = $miniTestCategoryType->getCategoryTypeParentLinks();
        return view("modules.minitest.index", ['parents'=> $parents ]); 
    }


    public function show($slug, MiniTestCategoryType $miniTestCategoryType) 
    {

        $category = MiniTestCategory::where('slug', $slug)->where('valid', true)->first();


        if ($category) {


            $breadcrumbs = $miniTestCategoryType->getParentLinks($category->question_category_type_id, true);

           
        
            return view("modules/minitest/show", compact('category', 'breadcrumbs') );   

        } else {
        
            return abort('404');
        
        }       
    
    }
}
