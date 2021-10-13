<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WritingEntries extends Model
{
    public $table = "writing_entries";

    protected $guarded = array('created_at', 'updated_at');

    public $timestamps = true;
}
