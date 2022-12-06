<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\MiniTestQuestion;


class MiniTestAnswerKey extends Model
{
    public $table = 'question_answer_key';

    public $timestamps = false;
    
    protected $guarded = [];  


    public function getQuestionsByCategory($categoryID) {

        return MiniTestQuestion::where('category_id', $categoryID)
                                ->orderby('id', 'ASC')
                                ->get();
    }



    public function getCorrectChoicesIDs($questionID) 
    {    
        return MiniTestAnswerKey::where('question_answer_key.question_id', $questionID)->pluck('choice_id');
    }

    public function getCorrectChoicesText($questionID) 
    {    
        return MiniTestAnswerKey::where('question_answer_key.question_id', $questionID)
                    ->join('question_choices', 'question_choices.id', '=', 'question_answer_key.choice_id')        
                    ->pluck('question_choices.choice');
    }


    public function getQuestionAnswersKeys($questionID) 
    {    
        return MiniTestAnswerKey::where('question_answer_key.question_id', $questionID)
                    ->join('question_choices', 'question_choices.id', '=', 'question_answer_key.choice_id')                    
                    ->get();
    }


    //get by category
    public function getAnswerKey($categoryID, $answers) 
    {
    
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

       
            $answer_question_id     = $answer['question_id'];
            $question               = $answer['question_text'];
            $choices                = $answer['choices'];

            //the selected choices
            $answer_choice_id       = $answer['selected_choice_id'];
            $selected_choice_text   = $answer['selected_choice_text'];
            

            //$results[$answer_question_id] = [

            $results[] = [                
                'question'              => $question,
                'choices'               => $choices,
                'correct_answer'        => (isset($correctAnswers[$answer_question_id]['answer']))? $correctAnswers[$answer_question_id]['answer'] : null,
                "question_id"           => $answer['question_id'],
                'answer_choice_id'      => $answer_choice_id,
                'your_answer'           => null,
                'is_correct'            => null,
                "totalQuestionCount"    => $totalQuestionCount 
            ];  
        }

        return $results;
    }


    public function getMultiAnswerKey($categoryID, $answers) {
    
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

       
            $answer_question_id     = $answer['question_id'];
            $question               = $answer['question_text'];
            $choices                = $answer['choices'];

            //the selected choices
            $answer_choices_id       = $answer['selected_choices_id'];
            $selected_choices_text   = $answer['selected_choices_text'];
            

            //$results[$answer_question_id] = [

            $results[] = [                
                'question'              => $question,
                'choices'               => $choices,
                'correct_answer'        => (isset($correctAnswers[$answer_question_id]['answer']))? $correctAnswers[$answer_question_id]['answer'] : null,
                "question_id"           => $answer['question_id'],
                'answer_choice_id'      => $answer_choices_id,
                'your_answer'           => null,
                'is_correct'            => null,               
            ];  
        }

        return $results;


    }


 
}
