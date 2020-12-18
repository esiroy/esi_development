<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionnaireItem extends Model
{
    public $table = 'questionnaire_item';
    
    protected $guarded = array('created_at', 'updated_at');
}
