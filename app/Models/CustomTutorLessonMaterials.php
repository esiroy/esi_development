<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomTutorLessonMaterials extends Model
{
    public $table = 'custom_tutor_lesson_materials';

    protected $guarded = array('created_at', 'updated_at');
}
