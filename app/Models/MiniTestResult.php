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


    public function getPreviousResults($userID, $numDays) 
    {

        $startDate = date('Y-m-d');
        $startDate = date('Y-m-d');

        $todayDateToUpper = date('Y-m-d 23:59:59');

        $prevDate = date('Y-m-d',(strtotime ( "-$numDays day" , strtotime ($todayDateToUpper) ) ));

        echo $todayDateToUpper ."<BR>";

        $results = MiniTestResult::where('user_id', $userID)
            ->whereBetween('time_started', array($prevDate, $todayDateToUpper))
            ->orderBy('time_started', 'ASC')            
            ->get();
    
        return $results;
    }

    /*
        @userID   : ID of user  
        @numDays    : Number of days of previously submitted results
    */
    public function countPreviousResults($userID, $numDays) 
    {
        $startDate = date('Y-m-d');
     
        $todayDateToUpper = date('Y-m-d 23:59:59');
        
        $prevDate = date('Y-m-d',(strtotime ( "-$numDays day" , strtotime ($todayDateToUpper) ) ));

        

        return  MiniTestResult::where('user_id', $userID)
            ->whereBetween('time_started', array($prevDate, $todayDateToUpper))
            ->orderBy('time_started', 'ASC')            
            ->count();
    }


    public function previousReultsbyDate($startDate, $previousDate) {
    
    
    }

    
}
