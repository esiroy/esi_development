<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\MiniTestQuestion;
use App\Models\MiniTestChoice;
use App\Models\MiniTestResult;

use Auth;

class QuestionsAPIController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function get(Request $request, MiniTestResult $miniTestResult)
    {


        $category_id = $request->category_id;


        $questions = MiniTestQuestion::where('category_id', $category_id)
                            //->where('valid', true)->get();
                            ->where('valid', true)->inRandomOrder()->get();


        //@get how many results submitted for past sevent days
        $miniTestCount = $miniTestResult->countPreviousResults(Auth::user()->id, 7);



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
                  
            ]);

        } else {        
        
            return Response()->json([
                "success"           => false,
                "message"           => "We have no questions for this category, please check again later",     
            ]);
        }
    }


}
