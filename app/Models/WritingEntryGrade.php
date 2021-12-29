<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WritingEntryGrade extends Model
{
    public $table = "writing_entry_grades";

    protected $guarded = array('created_at', 'updated_at');

    public $timestamps = true;    
}
