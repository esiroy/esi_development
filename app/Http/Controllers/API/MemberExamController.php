<?php

namespace App\Http\Controllers\API;

use App;
use App\Http\Controllers\Controller;
use App\Models\MemberExamScore;
use Auth;
use Illuminate\Http\Request;

class MemberExamController extends Controller
{
    public function getMemberExamScoreByType(Request $request)
    {

        $examScoreList = null;
        $examScoreLink = null;

        $memberID = isset($request['memberID']) ? $request['memberID'] : Auth::user()->id;
        $examTypes = MemberExamScore::where('user_id', $memberID)->select('exam_type')->distinct()->get();

        foreach ($examTypes as $value) 
        {
            $examType = $value->exam_type;
            $types[] = $examType;
            //get list of scores
            $examScores = MemberExamScore::where('user_id', $memberID)->where('exam_type', $examType)->orderBy('id', 'DESC')->get();
          
            $scores = $examScores;

            $examScoreList[$examType]['perPage'] = 1;            
            $examScoreList[$examType]['rows'] =  $examScores->count();
            
            //$examScoreList[$examType]['pages'] =  $examScores->lastPage();
            //$examScoreList[$examType]['currentPage'] =  $examScores->currentPage();
            
            foreach ($examScores as $examScore) {
                 $examScoreList[$examType]['items'][] = json_decode($examScore->exam_scores, true);                 
            }
        }

        if ($examTypes) {
        
            return Response()->json([
                'success' => true,
                'examTypes' => $types,               
                'examScoreList' => $examScoreList,
            ]);
        } else {
            return Response()->json([
                'success' => false,
                'message' => 'No record found for member exam types',
            ]);
        }
    }

    //Get Just the total for chars 
    public function getMemberExamScoreTotalByType(Request $request)
    {

        $yearAndMonths = getMonthAndYear(12);

        $examScoreList = null;
        $examScoreLink = null;

        $memberID = isset($request['memberID']) ? $request['memberID'] : Auth::user()->id;
        $examTypes = MemberExamScore::where('user_id', $memberID)->select('exam_type')->distinct()->get();
        foreach ($examTypes as $value) 
        {
            $examType = $value->exam_type;
            $types[] = $examType;

            $months = getMonths(12);

            //get list of scores
            
            foreach ($months as $month) 
            {                
                $examScores = MemberExamScore::where('user_id', $memberID)->where('exam_type', $examType)
                                ->select('exam_scores')
                                 ->whereYear('exam_date', date('Y', strtotime($month)) )
                                ->whereMonth('exam_date', date('m', strtotime($month)) )
                                ->orderBy('id', 'DESC')
                                ->get();             
               
                $total = 0;
                $count =  0;

                foreach ($examScores as $examScore) 
                {
                    $scoreData = json_decode($examScore->exam_scores, true);

                    if (isset($scoreData['total'])) 
                    {                    
                        $total = $total + $scoreData['total'];

                    } else if (isset($scoreData['overallBandScore'])) {

                        $total = $total + $scoreData['overallBandScore'];
                    }

                    $count++;
                }

                if ($count > 0) {
                     $avg = $total / $count; 
                } else {
                    $avg = 0;
                }
               

                $examScoreList[$examType]['totals'][] =  $total;
                $examScoreList[$examType]['avg'][] =  $avg;
                $examScoreList[$examType]['months'] = $yearAndMonths;
            }            
        }

        if ($examTypes) {
        
            return Response()->json([
                'success' => true,
                'examTypes' => $types,               
                'examScoreList' => $examScoreList,
            ]);
        } else {
            return Response()->json([
                'success' => false,
                'message' => 'No record found for member exam types',
            ]);
        }
    }



    public function getAllMemberExamScore(Request $request)
    {
        $limit = $request->get('limit');

        $memberID = isset($request['memberID']) ? $request['memberID'] : Auth::user()->id;

        $examScores = MemberExamScore::where('user_id', $memberID);
        $scores = $examScores->orderBy('id', 'DESC')->simplePaginate($limit);

        $examTypes = MemberExamScore::where('user_id', $memberID)->select('exam_type')->distinct()->get();

        $links = $scores->appends(['sort' => 'scores'])->links();

        if ($scores) {
            return Response()->json([
                'success' => true,
                'scores' => view('modules.member.popup.showAllMemberExamList', compact('scores', 'examTypes'))->render(),
                'links' => $links,
            ]);
        } else {
            return Response()->json([
                'success' => false,
                'message' => 'No record found for member',
            ]);
        }
    }

    public function getMemberLatestScore(Request $request, MemberExamScore $memberExamScore)
    {
        $memberID = isset($request['memberID']) ? $request['memberID'] : Auth::user()->id;
        return $memberExamScore->getMemberLatestScore($memberID);
    }

    public function addMemberExamScore(Request $request, MemberExamScore $memberExamScore)
    {
        $memberID = (isset($request['memberID'])) ? $request['memberID'] : Auth::user()->id;

        $examDate = $request['examDate'];
        $examType = $request['examType'];

        if (!($examDate == '' || $examType == '')) {
            $examTypeIndex = str_replace(' ', '-', $examType);

            $scores = $request['examScore']["$examTypeIndex"];
            $json_scores = json_encode($request['examScore']["$examTypeIndex"]);

            if ($memberExamScore->isScoresMissing($scores) == true) {
                return Response()->json([
                    'success' => false,
                    'message' => 'All fields are required, please check if the examination date, type and scores are all filled up',
                ]);
            } else {
                $data = [
                    'examDate' => $examDate,
                    'examType' => $examType,
                    'examScores' => $json_scores,
                ];
                $memberExamScore->addScore($memberID, $data);

                return Response()->json([
                    'success' => true,
                    'message' => 'Member Score Added',
                    'examDate' => ESIDateFormat($examDate),
                    'examType' => str_replace('_', ' ', $examType),
                    'examScores' => $json_scores,
                ]);
            }
        } else {
            return Response()->json([
                'success' => false,
                'message' => 'All fields are required, please check if the examination date, type and scores are all filled up',
            ]);
        }
    }
}
