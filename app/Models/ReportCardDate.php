<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportCardDate extends Model
{
    public $table = 'report_card_date';
    
    protected $guarded = array('created_at', 'updated_at');   

    public function getLatest($memberID)
    {
        return ReportCardDate::where('member_id', $memberID)->OrderByDesc('created_at')->first();
    }

}
