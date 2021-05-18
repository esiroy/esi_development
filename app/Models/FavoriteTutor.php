<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FavoriteTutor extends Model
{
    public $table = 'favorite_tutor';

    protected $guarded = array('created_at', 'updated_at');
}
