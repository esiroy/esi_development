<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MiniAnswerKey extends Model
{
    public $table = 'question_answer_key';
    public $timestamps = false;
    protected $guarded = [];  
}
