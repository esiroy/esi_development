<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Choices extends Model
{

   public $table = 'question_choices';

    
    protected $guarded = array('created_at', 'updated_at');
}
