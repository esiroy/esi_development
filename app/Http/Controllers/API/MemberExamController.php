<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MemberExamScore;

use Auth, App;
use DB;

class MemberExamController extends Controller
{

    public function getAllMemberExamScore(Request $request) 
    {
        $limit = $request->get('limit');        
        $scores = MemberExamScore::where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->paginate($limit);
        $links = $scores->links();
        if ($scores) {
            return Response()->json([
                "success" => true,
                'scores' => view('modules.member.popup.showAllMemberExamList', compact('scores'))->render(),
                'links' => $links     
            ]);
        } else {
            return Response()->json([
                "success" => false,
                "message" => "No record found for member",
            ]);
        }
    }


    public function getMemberLatestScore() 
    {
        $score = MemberExamScore::where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->first();
        if ($score) {
            return Response()->json([
                "success" => true,
                'examDate' => $score->exam_date,
                'examType' => $score->exam_type,
                'examScore' => $score->exam_score,                
                "message" => "member score fetched",
            ]);
        } else {
            return Response()->json([
                "success" => false,
                "message" => "No record found for member",
            ]);
        }
    }

    public function addMemberExamScore(Request $request) 
    {
        $memberExam = MemberExamScore::create([
            'user_id'   => Auth::user()->id,
            'exam_date' => $request['examDate'],
            'exam_type' => $request['examType'],
            'exam_score' => $request['examScore']
        ]);

        if ($memberExam) {
            return Response()->json([
                "success" => true,
                "message" => "member score added",
            ]);
        } else {
            return Response()->json([
                "success" => false,
                "message" => "Error white adding score",
            ]);
        }
    }
}
