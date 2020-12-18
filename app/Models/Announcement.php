<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{       
    public $table = 'announcement';

    protected $guarded = array('created_at', 'updated_at');

    //protected $casts = ['is_hidden' => 'boolean'];

}
