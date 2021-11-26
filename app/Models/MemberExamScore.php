<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberExamScore extends Model
{

    public $table = 'member_scores';

    protected $guarded = array('created_at', 'updated_at');
}
