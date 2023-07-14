<?php

namespace App\Http\Controllers\Admin\Modules;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\MiniTestCategory;
use App\Models\MiniTestCategoryType;

use Auth, Gate, Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class MiniTestCategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        abort_if(Gate::denies('minitest_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');


        $types = MiniTestCategoryType::where('valid', true)
                    ->orderBy('id', 'DESC')
                    ->paginate(Auth::user()->items_per_page);    


        $items = MiniTestCategory::where('valid', true)
                    ->orderBy('id', 'DESC')
                    ->paginate(Auth::user()->items_per_page);

        return view('admin.modules.minitest.categories.index', compact('items', 'types'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(Gate::denies('minitest_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        abort_if(Gate::denies('minitest_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');


        $name = $request->name;
        $instructions = $request->instructions;
        $timeLimit = $request->time_limit;
        $showMultiple = $request->show_multiple;    
        $randomizedQuestions = $request->randomized_questions;    
        $multipleCorrectAnswers = $request->multiple_correct_answers;
        $content = $request->content;    

        //disallow duplicate name
        $validator = Validator::make($request->all(), 
        [
            'name' => [
                'required',
                'max:80',
                Rule::unique('question_categories')->where('valid', true),
            ],           
        ]);

        if ($validator->fails()) 
        {
            return redirect()->route('admin.minitest.categories.index')->withErrors($validator)->withInput()->with('error_message', 'Error, category name is already taken!');
        } 

        
        $created = MiniTestCategory::create([
            'question_category_type_id' => $request->parent_id,
            'name'  => $name,
            'slug'  => str_replace(' ', '_', $name),
            'instructions'  => $instructions,
            'time_limit' => $timeLimit,
            'content'   => $content,
            'show_multiple' => (isset($showMultiple)) ? true : false,
            'randomized_questions' => (isset($randomizedQuestions)) ? true : false,
            'multiple_correct_answers' => (isset($multipleCorrectAnswers)) ? true : false, 
            'valid' => true,
        ]);

        if ($created) {
        
            return redirect()->route('admin.minitest.categories.index')->with('message', 'Category added successfully!');

        } else {
        
            return redirect()->route('admin.minitest.categories.index')->with('error_message', 'Category was not added due to an error, please try again!');        
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        abort_if(Gate::denies('minitest_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');   
      
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        abort_if(Gate::denies('minitest_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');


        $types = MiniTestCategoryType::where('valid', true)
                    ->orderBy('id', 'DESC')
                    ->get();    

        $item = MiniTestCategory::where('valid', true)->where('id', $id)->first();

        return view('admin.modules.minitest.categories.edit', compact('item', 'types'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {        

        abort_if(Gate::denies('minitest_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');


        $name = $request->name;
        $instructions = $request->instructions;
        $timeLimit = $request->time_limit;
        $showMultiple = $request->show_multiple;       
        $randomizedQuestions = $request->randomized_questions;
        $multipleCorrectAnswers = $request->multiple_correct_answers;
        $content = $request->content;       

       
        //disallow duplicate name
        $validator = Validator::make($request->all(), 
        [
            'name' => [
                'required',
                'max:80',
                Rule::unique('question_categories')->ignore($id)->where('valid', true),
            ],           
        ]);

        if ($validator->fails()) 
        {
            //return redirect()->route('admin.minitest.categories.index')->withErrors($validator)->withInput()->with('error_message', 'Error, category name is already taken!');
            return redirect()->back()->withErrors($validator)->withInput()->with('error_message', 'Error, category name is already taken!');
        } 



        $category = MiniTestCategory::where('id', $id)->first();

        $updated = $category->update([
            'question_category_type_id' => $request->parent_id,
            'name'  => $name,
            'slug'  => Str::of($name)->slug('-'),
            'instructions'  => $instructions,
            'time_limit' => $timeLimit,
            'show_multiple' => (isset($showMultiple)) ? true : false,
            'randomized_questions' => (isset($randomizedQuestions)) ? true : false,
            'multiple_correct_answers' => (isset($multipleCorrectAnswers)) ? true : false, 
            'content'   => $content,
            'valid' => true,
        ]);        

      if ($updated) {
        
            return redirect()->route('admin.minitest.categories.index')->with('message', 'Category updated successfully!');

        } else {
        
            return redirect()->route('admin.minitest.categories.index')->with('error_message', 'Category was not updated due to an error, please try again!');        
        }        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        abort_if(Gate::denies('minitest_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');


        $item = MiniTestCategory::where('valid', true)->where('id', $id)->first();

        $deleted = $item->update([
            'valid' => false
        ]);

        if ($deleted) 
        {        
            return redirect()->route('admin.minitest.categories.index')->with('message', 'Category deleted successfully!');

        } else {
        
            return redirect()->route('admin.minitest.categories.index')->with('error_message', 'Category was not deleted due to an error, please try again!');
        
        }
    }
}
