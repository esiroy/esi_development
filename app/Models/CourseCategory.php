<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseCategory extends Model
{
  public $table = 'course_category';

  protected $guarded = array('created_at', 'updated_at');

  public function getParents() 
  {
    return CourseCategory::where('course_category.valid', 1)->where('parent_course_category', null)->orderBy('sequence_number', 'asc')->get();
  }
  
  public function getSiblings($parentID) 
  {
    return CourseCategory::where('course_category.valid', 1)->where('parent_course_category', $parentID)->orderBy('sequence_number', 'asc')->get();
  }
  

  public function getCourses($parentID) {
    return CourseCategory::where('course_category.valid', 1)->where('course_category_image.valid', 1)
          ->select('course_category.*', 'course_category_image.path', 'course_category_image.filename', 'course_category_image.course_category_id', 'course_category.valid') 
          //->join('course_category_image', 'course_category_image.course_category_id', '=', 'course_category.id')
          ->join('course_category_image as course_category_image', 'course_category_image.course_category_id', '=', 'course_category.id', 'left outer')
          ->where('parent_course_category', $parentID)->orderBy('sequence_number', 'asc')->get();
  }


  

  public function getCourseInfo($id) 
  {
    return CourseCategory::where('course_category.valid', 1)         
            ->join('course_category_image as course_category_image', 'course_category_image.course_category_id', '=', 'course_category.id', 'left outer')
            ->where('course_category.id', $id)->orderBy('sequence_number', 'asc')->first();
          

  }
}
