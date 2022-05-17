<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MiniTestResult extends Model
{
    public $table = 'member_test_results';

    public $timestamps = false;
    
    protected $guarded = array('created_at', 'updated_at');


    public function add() 
    {
    
    
    }

    
}
