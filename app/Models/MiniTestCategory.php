<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MiniTestCategory extends Model
{
    public $table = 'question_categories';
    public $timestamps = false;
    protected $guarded = [];  

    public function getType() 
    {
         return $this->hasOne(MiniTestCategoryType::class, 'id', 'question_category_type_id');
    }
}
