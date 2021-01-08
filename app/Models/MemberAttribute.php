<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberAttribute extends Model
{
    public $table = 'member_attribute';

    public $timestamps = true;

    protected $guarded = array('created_at', 'updated_at');

    public function getMemberAttribute($memberID)
    {
        return MemberAttribute::where('member_id', $memberID)->get();
    }

    public function getLessonLimit($memberID)
    {
        $month = strtoupper(date("M"));
        $year = date('Y');

        return MemberAttribute::where('month', $month)
                ->where('year', $year)
                ->where('member_id', $memberID)
                ->first();
    }
}
