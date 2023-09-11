<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SatisfactionSurveyDetails extends Model
{
    public $table = 'lesson_survey_details';
    
    protected $guarded = array('created_at', 'updated_at');    
}
