<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Questionnaire extends Model
{
    public $table = 'questionnaire';
    
    protected $guarded = array('created_at', 'updated_at');

}
