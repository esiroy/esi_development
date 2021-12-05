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
        $memberID = isset($request['memberID']) ? $request['memberID'] : Auth::user()->id;

        $scores = MemberExamScore::where('user_id', $memberID)->orderBy('id', 'DESC')->paginate($limit);
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


    public function getMemberLatestScore(Request $request, MemberExamScore $memberExamScore) 
    {

        $memberID = isset($request['memberID']) ? $request['memberID'] : Auth::user()->id;
        return $memberExamScore->getMemberLatestScore($memberID );    
    }

    public function addMemberExamScore(Request $request, MemberExamScore $memberExamScore) 
    {
        $memberID   = (isset($request['memberID']))? $request['memberID'] : Auth::user()->id;

        $examDate = $request['examDate'];
        $examType = $request['examType'];
        
        if (!($examDate == "" || $examType == ""))
        {

            $examTypeIndex = str_replace(" ", "-", $examType);

            $scores = $request['examScore']["$examTypeIndex"];
            $json_scores = json_encode($request['examScore']["$examTypeIndex"]);

            if ($memberExamScore->isScoresMissing($scores) == true) 
            {

                return Response()->json([             
                    "success" => false,
                    "message" => "All fields are required, please check if the examination date, type and scores are all filled up",
                ]);

            } else {
                $data = [
                    "examDate"      => $examDate,
                    "examType"      => $examType,
                    "examScores"    => $json_scores
                ];
                $memberExamScore->addScore($memberID, $data);

                return Response()->json([             
                    "success" => true,
                    "message" => "Member Score Added",
                    "examDate"      => ESIDateFormat($examDate),
                    "examType"      => str_replace("_", " ", $examType),
                    "examScores"    => $json_scores
                ]);


            }

        } else {
            return Response()->json([
                "success" => false,
                "message" => "All fields are required, please check if the examination date, type and scores are all filled up",
            ]);
        }
    }
}
