<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseCategoryImage extends Model
{
    public $table = 'course_category_image';
    
    protected $guarded = array('created_at', 'updated_at');
}
