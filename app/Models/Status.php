<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    public $table = 'status';

    protected $guarded = array('created_at', 'updated_at');
}
