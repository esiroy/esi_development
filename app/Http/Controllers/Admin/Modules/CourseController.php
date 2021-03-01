<?php

namespace App\Http\Controllers\Admin\Modules;

use App\Http\Controllers\Controller;

use App\Models\LessonMaterial;
use App\Models\UploadFile;
use App\Models\CourseCategory;
use App\Models\CourseCategoryImage;
use Illuminate\Http\Request;
use Storage, Auth;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $categories = CourseCategory::orderBy('parent_course_category', 'ASC')->where('valid', 1)->paginate(Auth::user()->per_page);

        $optionCategories = CourseCategory::orderBy('parent_course_category', 'ASC')->where('valid', 1)->get();


        return view('admin.modules.course.index', compact('categories', 'optionCategories'));
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

    public function sortcategory($id = null) 
    {
        if (isset($id)) {
            $categories = CourseCategory::where('parent_course_category', $id)->orderBy('sequence_number', 'ASC')->where('valid', 1)->get();
        } else {
            $categories = CourseCategory::where('parent_course_category', null)->orderBy('sequence_number', 'ASC')->where('valid', 1)->get();
        }
        return view('admin.modules.course.sortcategory', compact('categories'));
    }
    
    public function savesortedcategory(Request $request, $id = null) 
    {   
        $sequence = 1;
        $courseCategoryObj = new CourseCategory();

        foreach($request->courseids as $course) 
        {
            $data = [
                'sequence_number' => $sequence
            ];
            $courseCategory = $courseCategoryObj->find($course);
            $courseCategory->update($data);        
            $sequence = $sequence + 1;
        }

        return back()->with('message', '<strong>Success!</strong> <div>Successfully updated.</div>');

    }


    public function uploadlessonmaterial(Request $request, UploadFile $uploadFile) 
    {    
        $errors = [];

        $storagePath = 'public/uploads/lesson_materials/';
 
        foreach ($request->file('upload') as $index => $file) 
        {
            $allowed = array('pdf');
         
            $ext = $file->getClientOriginalExtension();
            $filename = $file->getClientOriginalName();

            if (!in_array($ext, $allowed)) {
                $errors[] = "<div>Cannot upload $filename invalid format.</div>";
            } else {
                $uploadFileName = $uploadFile->uploadFile($storagePath, $file);
                $courseCategoryImage = [
                    'valid' => 1,
                    'course_category_id' => $request->course_category_id,
                    'filename' => $file->getClientOriginalName(),
                    'path' => $uploadFileName,
                    'sequence_number' => $index
                ];
                LessonMaterial::create($courseCategoryImage);
            }
        }

        if (count($errors) > 0) 
        {
            $error_message = "<strong>Error(s) to Required Fields</strong> " . implode($errors) ;
            return back()->with('error_message', $error_message);

        } else {

            return back()->with('message', '<strong>Success!</strong> <div>Lesson materials has been uploaded successfully.</div>');
        }
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

                //delete the images that are old or previous
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

        //$categories = CourseCategory::where('parent_course_category', '!=', $course->id)->get();

        $categories = CourseCategory::get();
        $courseCategory = CourseCategory::find($course->id);

        echo $courseCategory->parent_course_category;

        $courseCategoryImage = CourseCategoryImage::where('course_category_id', $course->id)->first();
        $lessonMaterials = LessonMaterial::where('course_category_id', $course->id)->get();
        return view('admin.modules.course.edit', compact('course', 'courseCategory', 'categories', 'courseCategoryImage', 'lessonMaterials'));
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

        if ($id == $request->parentid) 
        {
            return redirect()->back()->with('error_message', '<strong>Error</strong> <div>Please select different category as your parent</div>');
        } else {
            $data = [
                'name' => $request->name,
                'parent_course_category' => $request->parentid,
                'description' => $request->body,
                'valid' => true,
            ];
            $item = $course->update($data);
        }


        return redirect()->route('admin.course.index')->with('message', 'Course category has been updated successfully!');
    }
    

    public function destroyLessonMaterial(Request $request) 
    {
        $lessonMaterialObj = new LessonMaterial();        
        $lessonMaterial = $lessonMaterialObj->find($request->id);

        if ($lessonMaterial) {
            $file = storage_path('app/public/uploads/lesson_materials/' . basename($lessonMaterial->path));

            if (is_file($file)) {
                unlink($file);
                $deleted = $lessonMaterial->delete();
    
                return redirect()->route('admin.course.edit',  $lessonMaterial->course_category_id )->with('message', 'File has been deleted');
    
            } else {            
                return redirect()->route('admin.course.edit',  $lessonMaterial->course_category_id )->with('error_message', 'File does not exist');
            }   
        } else {           

            return redirect()->back()->with('error_message', 'File was not found on our records');

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
        $item = CourseCategory::find($id);
        $item->delete();
        return redirect()->back()->with('success', 'Category deleted successfully');
    }
}
