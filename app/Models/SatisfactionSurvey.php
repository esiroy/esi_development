<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SatisfactionSurvey extends Model
{
    public $table = 'lesson_survey';
    
    protected $guarded = array('created_at', 'updated_at');   
}
