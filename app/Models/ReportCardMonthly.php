<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportCardMonthly extends Model
{
    public $table = 'report_card_monthly';
    
    protected $guarded = array('created_at', 'updated_at');    
}
