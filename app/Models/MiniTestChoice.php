<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MiniTestChoice extends Model
{
    public $table = 'question_choices';

    public $timestamps = false;
    
    protected $guarded = [];  


    public function isCorrect() 
    {
        return $this->hasOne(MiniAnswerKey::class, 'choice_id', 'id');               
    }

}
