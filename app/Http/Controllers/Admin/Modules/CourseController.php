<?php

namespace App\Http\Controllers\Admin\Modules;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use App\Models\Tutor;
use App\Models\Member;
use App\Models\Lesson;
use App\Models\CourseCategory;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $categories = CourseCategory::orderBy('parent_course_category', 'ASC')
                        ->where('valid', 1)
                        ->paginate(50);
        
                        
        return view('admin.modules.course.index', compact('categories'));
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
        $data = [            
            'name'						=> $request->name,
            'parent_course_category'    => $request->parentid,
			'description'				=> $request->body,      
			'valid'						=> true
        ];
        $item = CourseCategory::create($data);

        return redirect()->route('admin.course.index')->with('message', 'Course category added successfully!');

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
    public function edit(CourseCategory $course)
    {

        $categories = CourseCategory::get();
        $courseCategory = CourseCategory::find($course->id);

       
								
        return view('admin.modules.course.edit', compact('course','courseCategory', 'categories'));        
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
        $course = CourseCategory::find($id);

        $data = [            
            'name'						=> $request->name,
            'parent_course_category'    => $request->parentid,
			'description'				=> $request->body,      
			'valid'						=> true
        ];
        $item = $course->update($data);

        return redirect()->route('admin.course.index')->with('message', 'Course category added successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = CourseCategory::find($id);
        $item->delete();
        return redirect()->back()->with('success','Category deleted successfully');
    }
}
