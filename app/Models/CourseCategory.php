<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseCategory extends Model
{
  public $table = 'course_category';

  protected $guarded = array('created_at', 'updated_at');
    
}
