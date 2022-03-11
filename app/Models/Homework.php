<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Homework extends Model
{
    public $table = 'homeworks';

    protected $guarded = array('created_at', 'updated_at');
}
