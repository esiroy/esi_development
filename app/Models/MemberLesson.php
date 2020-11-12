<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberLesson extends Model
{
    public $table = 'member_lessons';

    protected $guarded = array('created_at', 'updated_at');
}
