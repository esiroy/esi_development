<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tutor extends Model
{
    public $table = 'tutors';

    protected $guarded = array('created_at', 'updated_at');

   
    public function setBirthdateAttribute($value)
    {
       
        $this->attributes['birthdate'] = date('Y-m-d', strtotime($value)); 

    }
  
}
