<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserImage extends Model
{
    public $table = 'user_image';

    protected $guarded = array('created_at', 'updated_at');
}
