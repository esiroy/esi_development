<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LessonMaterial extends Model
{
    public $table = 'lesson_material';

    protected $guarded = array('created_at', 'updated_at');

    public function getLessonMaterial($id)
    {
        return LessonMaterial::where('valid', 1)->where('course_category_id', $id)->orderBy('sort_order', 'ASC')->get();
    }
}
