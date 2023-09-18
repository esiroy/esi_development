<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

use App\Models\ScheduleItem;

class ReportCard extends Model
{
    public $table = 'report_card';

    protected $guarded = array('created_at', 'updated_at');

    public function scheduleItem()
    {
        return $this->belongsTo(ScheduleItem::class, 'schedule_item_id');
    }
    
    
    public function getLatest($memberID)
    {
        //report cards
        $latestReportCard = ReportCard::select('report_card.*', 'schedule_item.lesson_time','schedule_item.schedule_status')
                                    ->join('schedule_item', 'report_card.schedule_item_id', '=', 'schedule_item.id')
                                    ->where('report_card.member_id', $memberID)
                                    //->orderBy('schedule_item.lesson_time', 'DESC')
                                    ->orderBy('schedule_item.updated_at', 'DESC')
                                    ->first();
       
        return $latestReportCard;
    }


    public function getReportbyScheduleItemID($schedule_item_id) {
        return ReportCard::where('schedule_item_id', $schedule_item_id)->first();
    }


    public function saveReportCard($reportCardData) 
    {
        return ReportCard::create([ 
            'schedule_item_id'  => $reportCardData['schedule_item_id'],
            'member_id'         => $reportCardData['member_id'],        
            'comment'           => $reportCardData['comment'],
            'grade'             => $reportCardData['grade'],
            //Materials
            'lesson_course'     => $reportCardData['lesson_course'],
            'lesson_material'   => $reportCardData['lesson_material'],
            'lesson_subject'    => $reportCardData['lesson_subject'],
            //Course 
            'course_category_id'    => null,
            'course_item_id'        => null,                
            'lesson_level'          => null,
            //Validity
            'valid'             =>  true,
        ]);
    }

}
