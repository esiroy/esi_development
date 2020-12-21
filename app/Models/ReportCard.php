<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportCard extends Model
{
    public $table = 'report_card';
    
    protected $guarded = array('created_at', 'updated_at');    
}
