<?php

namespace App\Http\Controllers\Admin\Modules;

use App\Http\Controllers\Controller;
use App\Models\CourseCategory;
use App\Models\CourseCategoryImage;
use Illuminate\Http\Request;
use Storage;

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

    public function uploadCourseImage(Request $request)
    {
        if ($file = $request->file('file')) {

            list($width, $height) = getimagesize($request->file('file'));

            if ($width <= 100 || $height <= 100) {

                //file path
                $storagePath = 'public/course_category_images/';

                $newFilename = time() . "_" . preg_replace('/\s+/', '_', $file->getClientOriginalName());
                $newFilename = mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $newFilename);
                // Remove any runs of periods (thanks falstro!)
                $newFilename = mb_ereg_replace("([\.]{2,})", '', $newFilename);

                //check if the filesize is not 0 / or cancelled
                if ($request->file('file')->getSize() > 0) {

                    //save in storage -> storage/public/course_category_images/
                    $path = $request->file('file')->storeAs(
                        //file path
                        $storagePath, $newFilename
                    );
                    //create public path -> public/storage/course_category_images/{folder_id}
                    $public_file_path = $storagePath . $newFilename;

                } else {
                    $public_file_path = null;
                }

                $courseImage = new CourseCategoryImage();

                $course_images = $courseImage->where('course_category_id', $request->course_category_id)->get();

                //delete the images that are old
                foreach ($course_images as $course_image) {
                    $file = storage_path('app/public/course_category_images/' . $course_image->filename);
                    if (is_file($file)) {
                        unlink($file);
                    } else {
                        //echo "File does not exist";
                    }

                    $deleted = $course_image->delete();
                }

                $courseCategoryImage = [
                    'valid' => 1,
                    'course_category_id' => $request->course_category_id,
                    'filename' => $newFilename,
                    'path' => $public_file_path,
                ];
                CourseCategoryImage::create($courseCategoryImage);

                return back()->with('message', '<strong>Success!</strong> <div>Image has been uploaded successfully.</div>');

            } else {
                return back()->with('error_message', '<strong>Error</strong> <div>Please upload an image less than or equal to 100 x 100 pixels.</div>');

            }

        } else {
            return back()->with('error_message', '<strong>Error(s) to Required Fields</strong> <div>You did not select any file.</div>');

        }
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
            'name' => $request->name,
            'parent_course_category' => $request->parentid,
            'description' => $request->body,
            'valid' => true,
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

        $courseCategoryImage = CourseCategoryImage::where('course_category_id', $course->id)->first();



        return view('admin.modules.course.edit', compact('course', 'courseCategory', 'categories', 'courseCategoryImage'));
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
            'name' => $request->name,
            'parent_course_category' => $request->parentid,
            'description' => $request->body,
            'valid' => true,
        ];
        $item = $course->update($data);

        return redirect()->route('admin.course.index')->with('message', 'Course category has been updated successfully!');
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
        return redirect()->back()->with('success', 'Category deleted successfully');
    }
}
