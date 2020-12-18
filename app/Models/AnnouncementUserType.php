<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnnouncementUserType extends Model
{
    public $table = 'announcement_usertypes';

    public $timestamps = false;

    protected $guarded = array();
}
