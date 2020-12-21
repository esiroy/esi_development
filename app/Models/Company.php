<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    public $table = 'company';

    protected $guarded = array('created_at', 'updated_at');
}
