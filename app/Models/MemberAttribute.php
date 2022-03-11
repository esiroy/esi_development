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
        //return MemberAttribute::where('member_id', $memberID)->where('valid', true)->orderby('created_at', 'DESC')->orderBy('year', 'ASC')->limit(12)->get();
        
        $items = MemberAttribute::where('member_id', $memberID)->where('valid', true)->orderby('created_at', 'DESC')->orderBy('year', 'ASC')->limit(12)->get();


        if (count($items) > 0) {
        
            foreach ($items as $item) 
            {
                $results[] = array(
                                'attribute' => $item->attribute,
                                'month' => $item->month,
                                'year' => $item->year,
                                'lesson_limit' => $item->lesson_limit,
                                'date' => $item->year ."-". date("m", strtotime($item->month)) ."-01",
                            );
            }

            
            usort($results, sortByDate('date'));
            $reversed_results = array_reverse($results);
            return $reversed_results;            
            
                        
        } else {
        
            return array();
        }

        
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
