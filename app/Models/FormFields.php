<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormFields extends Model
{
    //Writing Fields and Form Fields are merged

    public $table = "writing_fields";

    protected $guarded = array('created_at', 'updated_at');

    public $timestamps = false;
}
