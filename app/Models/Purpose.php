<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purpose extends Model
{
    public $table = 'member_purpose';
    
    protected $guarded = array('created_at', 'updated_at');
}
