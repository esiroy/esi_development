<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class MiniTestQuestion extends Model
{
    public $table = 'questions';

    public $timestamps = false;

    protected $guarded = [];  

    public function choices()
    {       
        $choices = $this->hasMany(MiniTestChoice::class, 'question_id', 'id');
        return $choices->where('valid', true);
    }

    public function answer() 
    {
         return $this->hasOne(MiniTestAnswerKey::class, 'question_id', 'id');        
    }

    public function answerText($choice_id) {
        return MiniTestChoice::find($choice_id)->choice;
    }
}
