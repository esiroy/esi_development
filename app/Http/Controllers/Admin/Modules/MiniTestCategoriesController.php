<?php

namespace App\Http\Controllers\Admin\Modules;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\MiniTestCategory;
use App\Models\MiniTest;

use Auth;

class MiniTestCategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = MiniTestCategory::where('valid', true)
                    ->orderBy('id', 'DESC')
                    ->paginate(Auth::user()->items_per_page);

        return view('admin.modules.minitest.categories.index', compact('items'));
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
        $name = $request->name;
        $instructions = $request->instructions;
        $timeLimit = $request->time_limit;
        
        $created = MiniTestCategory::create([
            'name'  => $name,
            'instructions'  => $instructions,
            'time_limit' => $timeLimit,
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $item = MiniTestCategory::where('valid', true)->where('id', $id)->first();

        return view('admin.modules.minitest.categories.edit', compact('item'));
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
        
        $name = $request->name;
        $instructions = $request->instructions;
        $timeLimit = $request->time_limit;


        $category = MiniTestCategory::where('id', $id)->first();

        $updated = $category->update([
            'name'  => $name,
            'instructions'  => $instructions,
            'time_limit' => $timeLimit,
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
        $item = MiniTestCategory::where('valid', true)->where('id', $id)->first();

        $updated = $item->update([
            'valid' => false
        ]);

        if ($updated) 
        {        
            return redirect()->route('admin.minitest.categories.index')->with('message', 'Category updated successfully!');

        } else {
        
            return redirect()->route('admin.minitest.categories.index')->with('error_message', 'Category was not updated due to an error, please try again!');
        
        }
    }
}
