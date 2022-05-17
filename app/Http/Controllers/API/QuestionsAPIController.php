<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\MiniTestQuestion;
use App\Models\MiniTestChoice;

class QuestionsAPIController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function get(Request $request)
    {
       
        $category_id = $request->category_id;


        $questions = MiniTestQuestion::where('category_id', $category_id)
                            //->where('valid', true)->get();
                            ->where('valid', true)->inRandomOrder()->get();

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
                "success"       => true,
                "message"       => "list has been successfully found",
                "questions"    => $question_items,
                  
            ]);

        } else {        
        
            return Response()->json([
                "success"           => false,
                "message"           => "We have no questions for this category, please check again later",     
            ]);
        }
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

  

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
