<?php

namespace App\Http\Controllers\Admin\Modules;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\MiniTestCategoryType;

use Auth, Gate, Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\Rule;


class MiniTestCategoryTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        abort_if(Gate::denies('minitest_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');


        $items = MiniTestCategoryType::where('valid', true)->orderBy('id', 'DESC')->paginate(Auth::user()->items_per_page);     

        return view('admin.modules.minitest.type.index', compact('items'));
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
        $parent_id = $request->parent_id;

        //disallow duplicate email and username
        $validator = Validator::make($request->all(), 
        [
            'name' => [
                'required',
                'max:80',
                Rule::unique('question_category_type')->where('valid', true),
            ],
           
        ]);

        if ($validator->fails()) 
        {
            return redirect()->route('admin.minitest.category.type.index')->withErrors($validator)->withInput()->with('error_message', 'Error, category type name is already taken!');
        } 

        $created = MiniTestCategoryType::create([
            'name'  => $name,     
            'parent_id'=>   $parent_id,        
            'valid' => true,
        ]);

        if ($created) {
        
            return redirect()->route('admin.minitest.category.type.index')->with('message', 'Category type added successfully!');

        } else {
        
            return redirect()->route('admin.minitest.category.type.index')->with('error_message', 'Category type was not added due to an error, please try again!');        
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
                    ->paginate(Auth::user()->items_per_page);


        $item = MiniTestCategoryType::where('valid', true)->where('id', $id)->first();

        return view('admin.modules.minitest.type.edit', compact('item', 'types'));        
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
        $name = $request->name;
        $parent_id = $request->parent_id;
      
        $validator = Validator::make($request->all(), 
        [
            'name' => [
                'required',
                'max:80',
                Rule::unique('question_category_type')->ignore($id)->where('valid', true),
            ],
        ]);

        if ($validator->fails()) 
        {
            //return redirect()->route('admin.minitest.category.type.index')->withErrors($validator)->withInput()->with('error_message', 'Error, category type name is already taken!');
            return redirect()->back()->withErrors($validator)->withInput()->with('error_message', 'Error, category type name is already taken!');
        } 


        $category = MiniTestCategoryType::where('id', $id)->first();

        $updated = $category->update([
            'name'  => $name,     
            'parent_id'=>   $parent_id,        
            'valid' => true,
        ]);        


        if ($updated) {
        
            return redirect()->route('admin.minitest.category.type.index')->with('message', 'Category Type updated successfully!');

        } else {
        
            return redirect()->route('admin.minitest.category.type.index')->with('error_message', 'Category Type was not updated due to an error, please try again!');        
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

        $item = MiniTestCategoryType::where('valid', true)->where('id', $id)->first();

        $deleted = $item->update([
            'valid' => false
        ]);

        if ($deleted) 
        {        
            return redirect()->route('admin.minitest.category.type.index')->with('message', 'Category Type deleted successfully!');

        } else {
        
            return redirect()->route('admin.minitest.category.type.index')->with('error_message', 'Category Type was not updated due to an error, please try again!');
        
        }
    }
}
