<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Auth;

class MiniTestResult extends Model
{
    public $table = 'member_test_results';

    public $timestamps = false;
    
    protected $guarded = array('created_at', 'updated_at');


    public function getResults($userID) 
    {
        return MiniTestResult::select('question_category_type.name as type', 'question_categories.name', 'member_test_results.*')
                    ->leftJoin('question_categories', 'question_categories.id', '=', 'member_test_results.question_category_id')           
                    ->leftJoin('question_category_type', 'question_category_type.id', '=', 'question_categories.question_category_type_id')                                          
                    ->where('member_test_results.user_id', $userID)
                    ->where('member_test_results.valid', true)
                    ->orderBy('member_test_results.time_started', 'DESC')             
                    ->paginate(Auth::user()->items_per_page);

    }



    public function getResultsParameter($userID) 
    {
        return MiniTestResult::select('question_category_type.name as type', 'question_categories.name', 'member_test_results.*')
                    ->leftJoin('question_categories', 'question_categories.id', '=', 'member_test_results.question_category_id')           
                    ->leftJoin('question_category_type', 'question_category_type.id', '=', 'question_categories.question_category_type_id')                                          
                    ->where('member_test_results.user_id', $userID)
                    ->where('member_test_results.valid', true)
                    ->orderBy('member_test_results.time_started', 'DESC')             
                    ->paginate(Auth::user()->items_per_page, ['*'], 'minitest');

    }


    public function getPreviousResults($userID, $numDays) 
    {

        $startDate = date('Y-m-d');
        $startDate = date('Y-m-d');

        $todayDateToUpper = date('Y-m-d 23:59:59');
        $prevDate = date('Y-m-d',(strtotime ( "-$numDays day" , strtotime ($todayDateToUpper) ) ));     

        $results = MiniTestResult::where('user_id', $userID)
            ->whereBetween('time_started', array($prevDate, $todayDateToUpper))
            ->orderBy('time_started', 'ASC')  
            ->where('valid', true)          
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
            ->where('valid', true)     
            ->count();
    }
    
}
