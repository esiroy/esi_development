<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\MiniTestCategory;
use App\Models\MiniTestCategoryType;




class MemberMiniTestCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, MiniTestCategoryType $miniTestcategoryType)
    {

        $categoryType = MiniTestCategoryType::where('valid', true)->where('id', $id)->first();
        $categories = MiniTestCategory::where('valid', true)->where('question_category_type_id', $id)->get();
       

        $breadcrumbs = $miniTestcategoryType->getParentLinks($id);

      

         //Get List 
        $categorySubTypes = MiniTestCategoryType::where('parent_id', $categoryType->id)->get();

        

        return view("modules.minitest.categories", compact('categoryType', 'categories', 'categorySubTypes', 'breadcrumbs')); 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}