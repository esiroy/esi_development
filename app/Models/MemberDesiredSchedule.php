<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberDesiredSchedule extends Model
{

    public $table = 'desired_schedule';

    protected $guarded = array('created_at', 'updated_at');

    public $timestamps = true;

    public function getMemberDesiredSchedule($memberID)
    {
        return MemberDesiredSchedule::where('member_id', $memberID)->where('valid', 1)->orderBy('id', 'ASC')->get();
    }

}
