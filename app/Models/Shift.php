<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    //public $table = 'shifts';

    protected $guarded = array('created_at', 'updated_at');

    public function tutors()
    {
        return $this->belongsToMany(Tutor::class);
    }   

}
