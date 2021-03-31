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



    public function getCurrentMonthLessonLimit($memberID)
    {
        $month = strtoupper(date("M"));
        $year = date('Y');

        return MemberAttribute::where('month', strtoupper($month))
                ->where('year', $year)
                ->where('member_id', $memberID)
                ->first();
    }    

    public function getLessonLimit($memberID, $month, $year)
    {
        //$month = strtoupper(date("M"));
        //$year = date('Y');

        return MemberAttribute::where('month', strtoupper($month))
                ->where('year', $year)
                ->where('member_id', $memberID)
                ->first();
    }
}
