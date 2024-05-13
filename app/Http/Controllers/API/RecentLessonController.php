<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ReportCard;

class RecentLessonController extends Controller
{
    public function getRecentLessonScore(Request $request, ReportCard $reportcards) 
    {

        $latestReportCard = $reportcards->getLatest($request->memberID);

        return Response()->json([
            "success"           => true,
            "message"           => "latest reportcard entry found",   
            "latestReportCard"  => $latestReportCard
        ]);        
    }

    //all lesson by MID with pagination
    public function getRecentAllLessonByMultiID(Request $request, ReportCard $reportcards) 
    {

        $memberID = $request['memberID'];
        $accountID = $request['accountID'];


        if ($accountID == null || $accountID == 1) { //merge result from account 1 and old account which is (null)
            //No multi account selected
            $reportCards = ReportCard::select('report_card.*', 'schedule_item.lesson_time', 'schedule_item.member_multi_account_id')
            ->join('schedule_item', 'report_card.schedule_item_id', '=', 'schedule_item.id')
            ->where('report_card.member_id', $memberID)
            ->where('report_card.valid', true)
            ->where(function ($query) use ($accountID) {
                $query->where('schedule_item.member_multi_account_id', 1)
                      ->orWhere('schedule_item.member_multi_account_id', null); // Main account
            })
            ->orderBy('schedule_item.lesson_time', 'DESC')
            ->paginate(1);         

        } else {

            $reportCards = ReportCard::select('report_card.*', 'schedule_item.lesson_time', 'schedule_item.member_multi_account_id')
            ->join('schedule_item', 'report_card.schedule_item_id', '=', 'schedule_item.id')
            ->where('report_card.member_id', $memberID)
            ->where('schedule_item.member_multi_account_id', $accountID)
            ->where('report_card.valid', true)
            ->orderBy('schedule_item.lesson_time', 'DESC')            
            ->paginate(1);            
        }


        //$reportCards['accountID'] =  $accountID;
          


        $allReportcards = ReportCard::select('report_card.*', 'schedule_item.lesson_time', 'schedule_item.member_multi_account_id')
            ->join('schedule_item', 'report_card.schedule_item_id', '=', 'schedule_item.id')
            ->where('report_card.member_id', $memberID)
            ->where('schedule_item.member_multi_account_id', $accountID)
            ->where('report_card.valid', true)
            ->orderBy('schedule_item.lesson_time', 'DESC')            
            ->get();

        return Response()->json([
            "success"      => true,
            "message"       => "latest reportcards entries found",                
            "member_multi_account_id"     => $accountID,
            "reportCards"   => $reportCards,
            "allReportcards" => $allReportcards
        ]);   

    }

    //single lesson by MID
    public function getRecentLessonScoreByMultiID(Request $request, ReportCard $reportcards) 
    {

        $memberID = $request['memberID'];
        $accountID = $request['accountID'];

        if ($accountID == null || $accountID == 1) { //merge result from account 1 and old account which is (null)

            //No multi account selected
            $latestReportCard = ReportCard::select('report_card.*', 'schedule_item.lesson_time', 'schedule_item.member_multi_account_id')
            ->join('schedule_item', 'report_card.schedule_item_id', '=', 'schedule_item.id')
            ->where('report_card.member_id', $memberID)
            ->where('report_card.valid', true)
            ->where(function ($query) use ($accountID) {
                $query->where('schedule_item.member_multi_account_id', 1)
                      ->orWhere('schedule_item.member_multi_account_id', null); // Main account
            })
            ->orderBy('schedule_item.lesson_time', 'DESC')
            ->first();         

        } else {

            $latestReportCard = ReportCard::select('report_card.*', 'schedule_item.lesson_time', 'schedule_item.member_multi_account_id')
            ->join('schedule_item', 'report_card.schedule_item_id', '=', 'schedule_item.id')
            ->where('report_card.member_id', $memberID)
            ->where('schedule_item.member_multi_account_id', $accountID)
            ->where('report_card.valid', true)
            ->orderBy('schedule_item.lesson_time', 'DESC')            
            ->first();               
        }

        //$latestReportCard = $reportcards->getLatestbyMultiID($request->memberID, $request->accountID);

        return Response()->json([
            "success"           => true,
            "message"           => "found",   
            "multiAccountID"     => $request->accountID,
            "latestReportCard"  => $latestReportCard
        ]);        
    }    


}
