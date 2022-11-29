<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MiniTestCategory;
use App\Models\MiniTestQuestion;
use App\Models\MiniTestChoice;
use App\Models\MiniTestResult;
use App\Models\MiniTestSetting;
use App\Models\MemberMiniTestSetting;


use Auth;

class QuestionsAPIController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function get(Request $request, MiniTestResult $miniTestResult, MiniTestSetting $miniTestSetting,
        MemberMiniTestSetting $memberMiniTestSetting)
    {

        $user = Auth::user();

        $category_id = $request->category_id;

        $category = MiniTestCategory::find($category_id);

        if ($category->randomized_questions == true) 
        {
            $questions = MiniTestQuestion::where('category_id', $category_id)->where('valid', true)->inRandomOrder()->get();
        } else {
            $questions = MiniTestQuestion::where('category_id', $category_id)->where('valid', true)->orderBy('id', 'ASC')->get();        
        }





        //@get how many results submitted for past seven days
        if ($memberMiniTestSetting->hasOverride($user->id) == true) {

            $duration           = $memberMiniTestSetting->getMiniTestDuration($user->id);   
            $miniTestlimit      = $memberMiniTestSetting->getMiniTestLimit($user->id);   

            $miniTestCount      = $miniTestResult->countPreviousResults($user->id, $duration);

        } else {
        
            $duration           = $miniTestSetting->getMiniTestDuration();
            $miniTestlimit      = $miniTestSetting->getMiniTestLimit(); 

            $miniTestCount      = $miniTestResult->countPreviousResults($user->id, $duration);
        
        }
        

        if (count($questions) >= 1) 
        {

            foreach ($questions as $key => $question) 
            {
                //$question_items[$question->id] =  $question;
                //$question_items[$question->id]['choices'] = MiniTestChoice::where('question_id', $question->id)->where('valid', true)->get();

                $question_items[$key] =  $question;
                $question_items[$key]['choices'] = MiniTestChoice::where('question_id', $question->id)->where('valid', true)->get();

            }

            return Response()->json([
                "success"                   => true,                
                "message"                   => "list has been successfully found",
                'miniTestSubmittedCount'    => $miniTestCount,
                "questions"                 => $question_items,
                'miniTestLimit'             => $miniTestlimit,
                'miniTestDuration'          => $duration,
                  
            ]);
        } else {        
        
            return Response()->json([
                "success"           => false,
                "message"           => "We have no questions for this category, please check again later",     
            ]);
        }
    }


}
