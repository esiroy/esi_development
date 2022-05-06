<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MiniTestCategory extends Model
{
    public $table = 'question_categories';
    public $timestamps = false;
    protected $guarded = [];  
}
