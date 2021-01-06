<?php

namespace App\Models;
use App\Models\Shift;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    //public $table = 'shifts';

    protected $guarded = array('created_at', 'updated_at');

    public function tutors()
    {
        return $this->belongsToMany(Tutor::class);
    }   

    public function getDuration($lesson_shift_id) {
        return Shift::find($lesson_shift_id)->duration;
    }
}
