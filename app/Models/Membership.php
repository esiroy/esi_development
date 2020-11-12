<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    //public $table = 'membership';

    protected $guarded = array('created_at', 'updated_at');
}
