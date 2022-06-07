<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Auth, DB;



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
    

    /* @description:
        Add Member Test Schedule (Overridden) Status with test count 
    */

    public function addMemberMiniTestSchedule( $miniTestCount, $type) 
    {
            //only (00,30) allowed
        $minutes = date('i');

        if ($minutes > 30) {
            $min = 30;
        } else {
            $min =  00;
        }

        $lessonData = [
            'lesson_time'       => date('Y-m-d H:i:00', strtotime(date('Y-m-d H:'.$min.':00'))),
            'member_id'         => Auth::user()->id,
            'tutor_id'          => null,
            'schedule_status'   => "MINITEST", 
            "memo"              => "MINITEST - ANSWERS QUESTION | minitest count - $miniTestCount | Type: ($type)",                      
            'valid'             => 0, //Overrided
        ];

        $schedule = ScheduleItem::create($lessonData);

        return $schedule;

    }

    public function initializeMiniTest($type, $categoryID, $answerKeys) 
    {

        $created = MiniTestResult::create([
            'user_id'                     => Auth::user()->id,
            'type'                        => $type,
            'question_category_id'        => $categoryID,                
            'total_questions'             => count($answerKeys),
            'member_answers'              => json_encode($answerKeys),                
            'time_started'                => now(),
            'correct_answers'             => 0,
            'valid'                       => true,
        ]); 
        
        return $created;

    }

    public function addMiniTestMonthlyResult($type, $categoryID, $results) 
    {
    
        $memberInfo = Auth::user()->memberInfo;

        $totalMonthlyCredits = $memberInfo->getMonthlyLessonsLeft();


        if ($totalMonthlyCredits > 0 )  
        {

            DB::beginTransaction();

            try {

                $created = MiniTestResult::create([
                    'type'                        => $type,
                    'question_category_id'        => $categoryID,
                    'user_id'                     => Auth::user()->id,
                    'time_started'                => now(),
                    //'time_ended'                 => null,
                    'total_questions'             => $totalQuestionCount,
                    'correct_answers'             => 0,
                    'member_answers'              => json_encode($results),
                    'valid'                       => true,
                ]);   

                if ($created) {

                    $scheduleAdded = $this->addMiniTestSchedule();


                    return $created;         
                }

                DB::commit();

            } catch (\Exception $e) {
              
                DB::rollBack();

                return Response()->json([
                    "success"                       => false,   
                    "message"                       => "Error Creating Mini-Test " . $e->getMessage(),

                ]);

            }


        } else {
        
           
                                    

        }

    }

    public function  responseMemberNoCredit($type) 
    {
        return Response()->json([
            "success"                       => false,          
            "message"                       => 'You have insufficient '. strtolower($type) .' credit',            
        ]);
    }
}
