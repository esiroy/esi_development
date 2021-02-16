<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ReportCard extends Model
{
    public $table = 'report_card';

    protected $guarded = array('created_at', 'updated_at');

    public function getLatest($memberID)
    {
        return ReportCard::where('member_id', $memberID)->OrderByDesc('created_at')->first();
    }


    public function getReportbyScheduleItemID($schedule_item_id) {
        return ReportCard::where('schedule_item_id', $schedule_item_id)->first();
    }
}
