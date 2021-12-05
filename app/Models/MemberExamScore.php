<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberExamScore extends Model
{

    public $table = 'member_scores';

    protected $guarded = array('created_at', 'updated_at');


    public function getMemberLatestScore($memberID) 
    {
        $score = MemberExamScore::where('user_id', $memberID)->orderBy('id', 'DESC')->first();
        if ($score) {
            return Response()->json([
                "success"                   => true,
                'examDate'                  => ESIDateFormat($score->exam_date),
                'examType'                  => str_replace("_", " ", $score->exam_type),
                'examScores'                => $score->exam_scores,
                "message"                   => "member score fetched",
            ]);
        } else {
            return Response()->json([
                "success" => false,
                "message" => "No record found for member",
            ]);
        }
    }


    public function addScore($memberID, $data)
    {
        $memberExam = MemberExamScore::create([
            'user_id'               => $memberID,
            'exam_date'             => $data['examDate'],
            'exam_type'             => $data['examType'],
            'exam_scores'           => $data['examScores'],
        ]); 

        return Response()->json([
            "success"               => true,
            'exam_date'             => ESIDateFormat($data['examDate']),
            'exam_type'             => $data['examType'],
            'exam_scores'           => $data['examScores'],
            "message"               => "member score added"
        ]);

    }

    public function isScoresMissing($scores) 
    {
        $isEmpty = false;
        foreach ($scores as $score) {
            if ($score === null) {
                $isEmpty = true;
            }
        }

        return $isEmpty;
    }
}
