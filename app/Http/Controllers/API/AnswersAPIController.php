<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use App\Models\MiniTestQuestion;
use App\Models\MiniTestAnswers;
use App\Models\MiniTestAnswerKey;
use App\Models\MiniTestResult;
use App\Models\ScheduleItem;
use App\Models\AgentTransaction;

use Auth;


class AnswersAPIController extends Controller
{


    public function addAnswerStartTime(Request $request, MiniTestResult $miniTestResult, AgentTransaction $agentTransaction)  
    {

        $categoryID = $request->get('category_id');
        $answers    = $request->get('answers');



        //get answer keys
        $answerKeys = MiniTestQuestion::select('question_answer_key.question_id', 'question_answer_key.choice_id', 'question_choices.choice')
                        ->leftJoin('question_answer_key', 'questions.id', '=', 'question_answer_key.question_id')
                        ->leftJoin('question_choices', 'question_choices.id', '=', 'question_answer_key.choice_id')
                        ->where('category_id', $categoryID)
                        ->where('questions.valid', true)
                        ->get();       


        $results = [];
        $correctAnswers = [];

        $correctAnswerCount = 0;
        $totalQuestionCount = count($answerKeys);

        //note: answer into correct answers
        if ($answerKeys) 
        {          
            foreach ($answerKeys as $answerKey) 
            {
                $correctAnswers[$answerKey->question_id]['choice_id']   = $answerKey->choice_id;
                $correctAnswers[$answerKey->question_id]['answer']      = $answerKey->choice;
            }
        }  

        foreach ($answers as $index => $answer) 
        {       

            $answer_choice_id       = $answer['selected_choice_id'];
            $answer_question_id     = $answer['question_id'];
            $question               = $answer['question_text'];
            $selected_choice_text   = $answer['selected_choice_text'];
            $choices                = $answer['choices'];

            //$results[$answer_question_id] = [

            $results[] = [
                
                'question'              => $question,
                'choices'               => $choices,
                'correct_answer'        => $correctAnswers[$answer_question_id]['answer'],                                
                "question_id"           => $answer['question_id'],
                'answer_choice_id'      => $answer_choice_id,
                'your_answer'           => null,
                'is_correct'            => null,
            ];  

        }


        $created = MiniTestResult::create([
            'question_category_id'        => $categoryID,
            'user_id'                     => Auth::user()->id,
            'time_started'                => now(),
            //'time_ended'                  => null,
            'total_questions'             => $totalQuestionCount,
            'correct_answers'             => 0,
            'member_answers'              => json_encode($results),
            'valid'                       => true,
        ]);


        if ($created) 
        {     

            /***************************************************************
            *    MEMBER BALANCE CHECKER - MONTHLY OR POINT BALANCE             
            *****************************************************************/
    
            $memberInfo = Auth::user()->memberInfo;

            $miniTestCount = $miniTestResult->countPreviousResults(Auth::user()->id, 7); //USeR , PREVIOUS NUMBER OF DAYS 


            if ($memberInfo->membership == "Monthly") 
            {
                if ($miniTestCount > 2) 
                {

                    /* deduction */
                    $deduction = 1;

                    //only (00,30) allowed
                    $minutes = date('i');
                    if ($minutes > 30) {
                        $min = 30;
                    } else {
                        $min =  00;
                    }


                    $totalMonthlyCredits = $memberInfo->getMonthlyLessonsLeft();

                    if ($totalMonthlyCredits >= 1)  
                    {
                    
                        $lessonData = [
                            'lesson_time'       => date('Y-m-d H:i:00', strtotime(date('Y-m-d H:'.$min.':00'))),
                            'member_id'         => Auth::user()->id,
                            'tutor_id'          => null,
                            'schedule_status'   => "MINITEST", 
                            "memo"              => "MINITEST - ANSWERS QUESTION | minitest count - $miniTestCount",                      
                            'valid'             => 0,
                        ];
                        $schedule = ScheduleItem::create($lessonData);
                    


                        if ($schedule) {
                        
                            return Response()->json([
                                "success"                   => true,  
                                "membershipType"            => $memberInfo->membership,       
                                "message"                   => "answers has initialized",
                                'id'                        => $created->id,
                                'miniTestSubmittedCount'    => $miniTestCount,
                                'deduction'                 => $deduction,
                                'totalMonthlyCredits'       => $totalMonthlyCredits,
                                'totalMonthlyCreditsFormatted'     => "(". number_format($totalMonthlyCredits, 2) .")"
                            ]); 
                        }                      
                    
                    } else {
                    
                        return Response()->json([
                            "success"                       => false,      
                            "membershipType"                => $memberInfo->membership,          
                            "message"                       => "You have insufficient monthly credit",
                            'miniTestSubmittedCount'        => $miniTestCount,
                            'deduction'                     => 0, //override dediction
                            'totalMonthlyCredits'           => $totalMonthlyCredits,
                            'totalMonthlyCreditsFormatted'  => "(". number_format($totalMonthlyCredits, 2) .")"
                        
                        ]);     

                    
                    }

                  
                } else {
                
                    $totalMonthlyCredits = $memberInfo->getMonthlyLessonsLeft();
                  
                
                    return Response()->json([
                        "success"                       => true,      
                        "membershipType"                => $memberInfo->membership,          
                        "message"                   => "answers has initialized",
                        'miniTestSubmittedCount'        => $miniTestCount,
                        'deduction'                     => 0, //override dediction
                        'totalMonthlyCredits'           => $totalMonthlyCredits,
                        'totalMonthlyCreditsFormatted'  => "(". number_format($totalMonthlyCredits, 2) .")"
                    
                    ]);                   
                }


            } 
            else if ($memberInfo->membership == "Point Balance" || $memberInfo->membership == "Both")             
            {      

                $credits = $agentTransaction->getCredits(Auth::user()->id);

                if ($credits >= 1) 
                {
                   

                    if ($miniTestCount > 2) 
                    {
                        /* deduction */
                        $deduction = 1;

                    
                        /* Deduct Point */
                        $agentCredit = [
                            'valid' => 1,
                            'transaction_type' => 'AGENT_SUBTRACT',
                            'agent_id' => Auth::user()->memberInfo->agent_id,
                            'member_id' => Auth::user()->memberInfo->user_id,
                            'lesson_shift_id' =>  Auth::user()->memberInfo->lesson_shift_id,
                            'created_by_id' => Auth::user()->id,
                            'amount' => $deduction,
                            'price' => 1,
                            'remarks' => "MINITEST - ANSWERS QUESTION | minitest count - $miniTestCount", 
                        ];
                        AgentTransaction::create($agentCredit); 
                    

                    } else {
                    
                        $deduction = 0;
                    }

                    $totalCredits = $agentTransaction->getCredits(Auth::user()->id);

                
                    return Response()->json([
                        "success"                   => true,        
                        "membershipType"            => $memberInfo->membership,     
                        "message"                   => "answers has initialized",
                        'id'                        => $created->id,
                        'miniTestSubmittedCount'    => $miniTestCount,
                        'deduction'                 => $deduction,
                        'totalCredits'              => $totalCredits,
                        'totalCreditsFormatted'     => "(". number_format($totalCredits, 2) .")"
                    
                    ]);

                
                } else {


                    $totalCredits = $agentTransaction->getCredits(Auth::user()->id);
                
                    return Response()->json([
                        "success"                   => false,      
                        "membershipType"            => $memberInfo->membership,          
                        "message"                   => "You have insufficient credit",
                        'totalCredits'              => $totalCredits,
                        'totalCreditsFormatted'     => "(". number_format($totalCredits, 2) .")"
                    
                    ]);
                
                }



            } 




        } else {
        
            return Response()->json([
                "success"               => false,            
                "message"               => "Error: Answers has failed to initialized",
               
            ]);
        
        }

    }




    public function post(Request $request) 
    {

        $miniTestID = $request->get('miniTestID');

        $categoryID = $request->get('category_id');
        $answers    = $request->get('answers');

        //get answer keys
        $answerKeys = MiniTestQuestion::select('question_answer_key.question_id', 'question_answer_key.choice_id', 'question_choices.choice')
                        ->leftJoin('question_answer_key', 'questions.id', '=', 'question_answer_key.question_id')
                        ->leftJoin('question_choices', 'question_choices.id', '=', 'question_answer_key.choice_id')
                        ->where('category_id', $categoryID)
                        ->where('questions.valid', true)
                        ->get();       


        $results = [];
        $correctAnswers = [];

        $correctAnswerCount = 0;
        $totalQuestionCount = count($answerKeys);

        //note: answer into correct answers
        if ($answerKeys) 
        {                  
            foreach ($answerKeys as $answerKey) 
            {
                $correctAnswers[$answerKey->question_id]['choice_id']   = $answerKey->choice_id;
                $correctAnswers[$answerKey->question_id]['answer']      = $answerKey->choice;
            }
        }       


        //answers
        foreach ($answers as $index => $answer) 
        {
            $answer_choice_id       = $answer['selected_choice_id'];
            $answer_question_id     = $answer['question_id'];
            $question               = $answer['question_text'];
            $selected_choice_text   = $answer['selected_choice_text'];
            $choices                = $answer['choices'];

            if ($answer_choice_id == $correctAnswers[$answer_question_id]['choice_id']) 
            {

                //correct answers
                $correctAnswerCount++;
            
                //$results[$answer_question_id] = [
                $results[] = [
                    'question'              => $question,
                    'choices'               => $choices,

                    'correct_answer'        => $correctAnswers[$answer_question_id]['answer'],
                    "question_id"           => $answer['question_id'],
                    'answer_choice_id'      => $answer_choice_id,

                    'your_answer'           => $selected_choice_text,
                    'is_correct'            => true,
                ]; 

            } else if ($answer_choice_id != $correctAnswers[$answer_question_id]['choice_id']) {

                //$results[$answer_question_id] = [
                $results[] = [
                    'question'              => $question,
                    'choices'               => $choices,

                    'correct_answer'        => $correctAnswers[$answer_question_id]['answer'],
                    "question_id"           => $answer['question_id'],
                    'answer_choice_id'      => $answer_choice_id,

                    'your_answer'           => $selected_choice_text,
                    'is_correct'            => false,
                ];             
            
            } else {
            
        
                //$results[$answer_question_id] = [
                $results[] = [
                
                    'question'              => $question,
                    'choices'               => $choices,

                    'correct_answer'        => $correctAnswers[$answer_question_id]['answer'],
                    "question_id"           => $answer['question_id'],
                    'answer_choice_id'      => null,

                    'your_answer'           => null,
                    'is_correct'            => null,
                ];            
            
            }
        
        }



        $miniTestResult = MiniTestResult::where("id", $miniTestID )->first();

        if ($miniTestResult) {
        
            $miniTestResult->update([
                'question_category_id'        => $categoryID,
                'user_id'                      => Auth::user()->id,
                //'time_started'              => TIME_STARTED,
                'time_ended'                  => now(),
                'total_questions'             => $totalQuestionCount,
                'correct_answers'             => $correctAnswerCount,
                'member_answers'              => json_encode($results) 
            ]);        
        

            return Response()->json([
                "success"               => true,
                "message"               => "answers has been successfully saved",
                'total_correct_answers' => $correctAnswerCount,
                'total_questions'       => $totalQuestionCount,
                'results'               => $results
            ]);
            
        } else {
        
            return Response()->json([
                "success"               => false,
                "message"               => "Error: answers has been failed to saved",
            ]); 
        
        }
    }


    public function post_old(Request $request)
    {
        $categoryID = $request->get('category_id');     
        $results = [];
        $correctAnswers = [];

        $correctAnswerCount = 0;
        $totalQuestionCount = 0;

        

        $answerKeys = MiniTestQuestion::select('question_answer_key.question_id', 'question_answer_key.choice_id', 'question_choices.choice')
                        ->leftJoin('question_answer_key', 'questions.id', '=', 'question_answer_key.question_id')
                        ->leftJoin('question_choices', 'question_choices.id', '=', 'question_answer_key.choice_id')
                        ->where('category_id', $categoryID)
                        ->where('questions.valid', true)
                        ->get();

        if ($answerKeys) 
        {

            $totalQuestionCount = count($answerKeys);
  
            foreach ($answerKeys as $answerKey) 
            {
                //correct question id : choice id
                $correctAnswers[$answerKey->question_id]['choice_id'] = $answerKey->choice_id;
                $correctAnswers[$answerKey->question_id]['answer'] = $answerKey->choice;
            }

            //compare submitted answers to correct answers 
            $submitted_answers = $request->answers;

            foreach ($submitted_answers as $key => $submitted_answer) 
            {

                $questions_id = $submitted_answer['question_id'];

                if ($submitted_answer['choice_id'] == $correctAnswers[$questions_id]['choice_id']) 
                {

                    $correctAnswerCount++;
                    $is_correct = true;

                } else {
                    $is_correct = false;            
                }

                $results[$questions_id] = [
                    'is_correct'            => $is_correct,
                    'question'              => $submitted_answer['question'],
                    'correct_answer'        => $correctAnswers[$submitted_answer['question_id']]['answer'],
                    'your_answer'           => $submitted_answer['choice'],
                    //'question_id'      => $submitted_answer['question_id'],
                    //'choice_id'       => $submitted_answer['choice_id'],
                ]; 

            }





            return Response()->json([
                    "success"               => true,
                    "message"               => "list has been successfully saved",
                    'results'               => $results,
                    "submitted_answers"     => $submitted_answers,
                    "correctAnswers"        => $correctAnswers,
                    'total_questions'       => $totalQuestionCount,
                    'total_correct_answers' => $correctAnswerCount,

            ]);



        } else {
        
        
            return Response()->json([
                    "success"               => false,
                    "message"               => "Error, we can't save the result please try again later.",      
            ]);
        
        }


    }    
}
