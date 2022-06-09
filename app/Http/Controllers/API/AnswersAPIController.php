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
use App\Models\MiniTestSetting;
use App\Models\MemberMiniTestSetting;



use Auth, DB;


class AnswersAPIController extends Controller
{


    public function addAnswerStartTime(Request $request, MiniTestResult $miniTestResult, 
            AgentTransaction $agentTransaction, MiniTestAnswerKey $miniTestAnswerKey,
            MiniTestSetting $miniTestSetting, MemberMiniTestSetting $memberMiniTestSetting)  
    {


        $user = Auth::user();
        $memberInfo = Auth::user()->memberInfo;

        $categoryID = $request->get('category_id');
        $answers    = $request->get('answers');

        //get answer keys and count questions
        $answerKeys             = $miniTestAnswerKey->getAnswerKey($categoryID, $answers);        
        $totalQuestionCount     = count($answerKeys);

        //Member Settings has overrides minitest duration settings and minitest limit
        if ($memberMiniTestSetting->hasOverride($user->id) == true) {

            //Member Settings overrides General Settings
            $duration      = $memberMiniTestSetting->getMiniTestDuration($user->id);   
            $miniTestlimit = $memberMiniTestSetting->getMiniTestLimit($user->id);   
        
            //count results for minitest
            $miniTestCount = $miniTestResult->countPreviousResults($user->id, $duration);

        } else {        

            //General Mini Test Settings 
            $duration      = $miniTestSetting->getMiniTestDuration(); 
            $miniTestlimit = $miniTestSetting->getMiniTestLimit(); 

            //count results for minitest
            $miniTestCount = $miniTestResult->countPreviousResults($user->id, $duration);  
        }


        $type = ($miniTestCount >= $miniTestlimit) ? $memberInfo->membership : 'Free';  


        if ($type == "Monthly") 
        {

            $totalMonthlyCredits = $memberInfo->getMonthlyLessonsLeft();

            if ($totalMonthlyCredits > 0) 
            {
                DB::beginTransaction();

                $created        = $miniTestResult->initializeMiniTest($type, $categoryID, $answerKeys);
                $scheduleAdded  = $miniTestResult->addMemberMiniTestSchedule( $miniTestCount, $type );

                DB::commit();
            
                if ($created) 
                {

                    $getUpdatedMonthlyCredits = $memberInfo->getMonthlyLessonsLeft();
                
                    return Response()->json([
                        "success"                           => true,  
                        "membershipType"                    => $memberInfo->membership,       
                        "message"                           => "answers has initialized",
                        'id'                                => $created->id,
                        'miniTestSubmittedCount'            => $miniTestResult->countPreviousResults($user->id, 7),
                        'deduction'                         => 1,
                        'totalMonthlyCredits'               => $getUpdatedMonthlyCredits,
                        'totalMonthlyCreditsFormatted'     => "(". number_format($getUpdatedMonthlyCredits, 2) .")"
                    ]);
                }

            } else {
            
                return $miniTestResult->responseMemberNoCredit($type);

            }

        } else if ($type == "Point Balance" || $type == "Both") {
            
            
            if ($memberInfo->isMemberCreditExpired($user->id) == true) {
            
                return $miniTestResult->responseMemberExpiredCredit($type);
                exit();
            }


            $credits = $agentTransaction->getCredits(Auth::user()->id);

            if ($credits > 0) 
            {          

                DB::beginTransaction();

                $created = $miniTestResult->initializeMiniTest($type, $categoryID, $answerKeys);

                $scheduleAdded = $miniTestResult->addMemberMiniTestSchedule( $miniTestCount, $type );

                DB::commit();


                //Update MiniTest Count after adding
                $miniTestCount = $miniTestResult->countPreviousResults($user->id, $duration);     
            
                if ($created) 
                {                       
                    $agentCredit = [
                        'valid' => 1,
                        'transaction_type' => 'AGENT_SUBTRACT',
                        'agent_id' => Auth::user()->memberInfo->agent_id,
                        'member_id' => Auth::user()->memberInfo->user_id,
                        'lesson_shift_id' =>  Auth::user()->memberInfo->lesson_shift_id,
                        'created_by_id' => Auth::user()->id,
                        'amount' => 1,
                        'price' => 1,
                        'remarks' => "MINITEST - ANSWERS QUESTION | minitest count - $miniTestCount", 
                    ];
                    AgentTransaction::create($agentCredit); 


                    $totalCredits = $agentTransaction->getCredits(Auth::user()->id);
                
                    return Response()->json([
                        "success"                   => true,        
                        "membershipType"            => $memberInfo->membership,     
                        "message"                   => "answers has initialized",
                        'id'                        => $created->id,
                        'miniTestSubmittedCount'    => $miniTestCount,
                        'deduction'                 => 1,
                        'totalCredits'              => $totalCredits,
                        'totalCreditsFormatted'     => "(". number_format($totalCredits, 2) .")"
                    
                    ]);

                }            

            } else {
            
                return $miniTestResult->responseMemberNoCredit($type);

            }


            
        } else if ($type == "Free") {
        

            DB::beginTransaction();

            $freeCreated = $miniTestResult->initializeMiniTest($type, $categoryID, $answerKeys);

            $scheduleAdded = $miniTestResult->addMemberMiniTestSchedule( $miniTestCount, $type );


            if ($memberInfo->membership  == "Point Balance") 
            {
                $agentCredit = [
                    'valid' => 1,
                    'transaction_type'  => 'AGENT_SUBTRACT',
                    'agent_id'          => $memberInfo->agent_id,
                    'member_id'         => $memberInfo->user_id,
                    'lesson_shift_id'   => $memberInfo->lesson_shift_id,
                    'created_by_id'     => $memberInfo->user_id,
                    'amount'            => 0,
                    'price'             => 0,
                    'remarks'           => "MINITEST - ANSWERS QUESTION | $type | minitest count - $miniTestCount", 
                ];
                AgentTransaction::create($agentCredit); 
            }


            DB::commit();
        
            if ($freeCreated) 
            {

                $getUpdatedMonthlyCredits = $memberInfo->getMonthlyLessonsLeft();
            
                $totalCredits = $agentTransaction->getCredits(Auth::user()->id);

                return Response()->json([
                    "success"                           => true,  
                    "membershipType"                    => $memberInfo->membership,       
                    "message"                           => "answers has initialized",
                    'id'                                => $freeCreated->id,
                    'miniTestSubmittedCount'            => $miniTestResult->countPreviousResults($user->id, 7),
                    'deduction'                         => 1,
                    'totalMonthlyCredits'               => $getUpdatedMonthlyCredits,
                    'totalMonthlyCreditsFormatted'     => "(". number_format($getUpdatedMonthlyCredits, 2) .")",
                    'totalCredits'                      => $totalCredits,
                    'totalCreditsFormatted'             => "(". number_format($totalCredits, 2) .")"                    
                ]);
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
