<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use App\Models\Questions;
use App\Models\Answers;
use App\Models\AnswerKey;

class AnswersAPIController extends Controller
{
    
    public function get()
    {
    
    }

    public function post(Request $request)
    {

      $results = [];


        $answerKeys = Questions::select('question_answer_key.question_id', 'question_answer_key.choice_id', 'question_choices.choice' )
                        ->leftJoin('question_answer_key', 'questions.id', '=', 'question_answer_key.question_id')

                        ->leftJoin('question_choices', 'question_choices.id', '=', 'question_answer_key.choice_id')

                        ->where('questions.valid', true)
                        ->get();

        $correctAnswers = [];
        
        foreach ($answerKeys as $answerKey) 
        {
            //correct question id : choice id
            $correctAnswers[$answerKey->question_id]['choice_id'] = $answerKey->choice_id;
            $correctAnswers[$answerKey->question_id]['answer'] = $answerKey->choice;
        }

        //compare submitted answers to correct answers 
        $submitted_answers = $request->answers;

        foreach ($submitted_answers as $key => $submitted_answer) {

            $questions_id = $submitted_answer['question_id'];

            if ($submitted_answer['choice_id'] == $correctAnswers[$questions_id]['choice_id'] ) {
                $is_correct = true;
            } else {
                $is_correct = false;            
            }

            $results[$questions_id] = [
                'is_correct'        => $is_correct,
                'question'          => $submitted_answer['question'],
                'correct_answer'    => $correctAnswers[$submitted_answer['question_id']]['answer'],
                'your_answer'       => $submitted_answer['choice']
                //'question_id'   => $submitted_answer['question_id'],
                //'choice_id'     => $submitted_answer['choice_id'],
            ];            
        }

       return Response()->json([
                "success"   => true,
                "message"   => "list has been successfully saved",
                'results'   => $results,
                "submitted_answers"   =>  $submitted_answers,
                "correctAnswers" => $correctAnswers
       ]);
    }    
}
