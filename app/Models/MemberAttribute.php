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
        return MemberAttribute::where('member_id', $memberID)->where('valid', true)->orderBy('year', 'ASC')->get();
    }



    public function getCurrentMonthLessonLimit($memberID)
    {
        $month = strtoupper(date("M"));
        $year = date('Y');

        $lessonLimit = MemberAttribute::where('month', strtoupper($month))->where('year', $year)->where('member_id', $memberID)->first();

        if ($lessonLimit) {
            return $lessonLimit;
        } else {
            return null;
        }
    }    

    public function getLessonLimit($memberID, $month, $year)
    {
        $lessonLimit = MemberAttribute::where('month', strtoupper($month))->where('year', $year)->where('member_id', $memberID)->first();
        if ($lessonLimit) {
            return $lessonLimit;
        } else {
            return null;
        }        
    }
}
