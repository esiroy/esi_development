<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MiniTestAnswerKey extends Model
{
    public $table = 'question_answer_key';

    public $timestamps = false;
    
    protected $guarded = [];  
}
