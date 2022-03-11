<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{

    /*
    protected $fillable = [
        'form_name', 
        'form_description',
        'created_at',
        'updated_at',
    ];*/


    protected $guarded = array('created_at', 'updated_at');

}
