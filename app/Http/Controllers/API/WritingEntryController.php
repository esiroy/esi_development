<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AgentTransaction;
use App\Models\WritingEntries;
use App\Models\Member;
use App\Models\User;
use Auth, Config;

class WritingEntryController extends Controller
{

    public function checkCredits(Request $request, Member $member, AgentTransaction $agentTransaction, WritingEntries $writingEntries) 
    {     
        $userID         = $request->userID;
        $tutorID        = $request->tutorID;
        $memberInfo     = $member->where('user_id', Auth::user()->id )->first();
        $message        = null;

        if (Auth::user()->user_type == 'ADMINISTRATOR' || Auth::user()->user_type == 'MANAGER' ) 
        {
            //MANAGER CAN GO SUBMIT ANYHTHING ON WRITING
            return Response()->json([
                "success"           => true,
                "message"           => "Account is credit for this type of account is not applicable",
                "totalPointsLeft"   => 1
            ]);

        } else if (Auth::user()->user_type == 'MEMBER' ) {
        
             //Get the submitted deduction points
             $wordPointDeduction = $writingEntries->getWordPointDeduct($request->words);

             //double the deduction if client selected a teacher
             if (isset($tutorID)) {
                $wordPointDeduction = $wordPointDeduction * 2;
             }

            if ($memberInfo->membership == "Monthly") 
            {
                $credits = $member->getMonthlyLessonsLeft();
                $totalPointsLeft = $credits - $wordPointDeduction;
                $errorMessage = "Sorry, you don't have enough monthly points for this entry.";

            } else if ($memberInfo->membership  == "Point Balance" ||  $memberInfo->membership  == "Both") {

                $credits = $agentTransaction->getCredits( Auth::user()->id ); 
                $totalPointsLeft = $credits - $wordPointDeduction;

                $errorMessage = "Sorry, you don't have enough points for this entry.";

            }
        }        



        if ($totalPointsLeft < 0) 
        {
            $success =  false;
            $message = $errorMessage;
            $message .= "<p>This entry requires $wordPointDeduction  points,  You only have  $credits point(s) left </p>";
        } else {
            $success =  true;
            $message = "<p>Congratulations, You have sufficient credits </p>";
        }

        return Response()->json([
            "success"  => $success,   
            "message" =>  $message,
            "membership" => $memberInfo->membership,
            "credits"   => $credits,
            "totalDeductedPoints" => $wordPointDeduction,
            "membership" => $memberInfo->membership,
            "getMonthlyLessonsLeft" => $getMonthlyLessonsLeft ?? "not applicable",
            "totalPointsLeft" => $totalPointsLeft
        ]); 
    }


    public function checkMemberCredits(Request $request, Member $member, AgentTransaction $agentTransaction, WritingEntries $writingEntries) 
    {
        $hasAttachment  = $request->hasAttachment;
        $is_appointed   = $request->appointed;
        $memberID       = $request->memberID;        
        $tutorID        = $request->tutorID;
     
        $wordPointDeduction = 0;
        $totalDeduction     = 0;
        $deposit            = 0;

        $user               = User::find($memberID);   
        $memberInfo         = $member->where('user_id', $memberID )->first();                   
        $writingEntry       = $writingEntries->find($request->entryID);
        $wordPointDeduction = $writingEntries->getWordPointDeduct($request->words);

        if (isset($hasAttachment)) 
        {
            if (!isset($request->words) || $request->words == 0) 
            {
                return Response()->json([
                    "success"  => false,   
                    "message" => "Please enter how many words on the file attachment",               
                ]); 

                exit();
                
            } else if ($wordPointDeduction == false) {

                return Response()->json([
                    "success"  => false,   
                    "message" => "800 words limit exceeded",               
                ]);  

                exit();
            }
        }



        if ($user) 
        {
            if ($user->user_type == 'ADMINISTRATOR' || $user->user_type == 'MANAGER' ) 
            {          
                return Response()->json([
                    "success"           => true,
                    "message"           => "Account  $user->user_type type of account is not applicable for member credit checker",
                    "totalPointsLeft"   => 1 //(this will auto submit)
                ]);
            
            } else if ($user->user_type == 'MEMBER' ) {

                if ($memberInfo) 
                {                

                    //get the credits of user
                    if ($memberInfo->membership == "Monthly") 
                    { 
                        $point_type = "monthly points";

                        $credits = $member->getMemberMonthlyLessonsLeft($memberID);

                    } else if ($memberInfo->membership  == "Point Balance" ||  $memberInfo->membership  == "Both") {

                        $point_type = "points";
    
                        $credits = $agentTransaction->getCredits($memberID);   
                    } else {                    
                        $credits = 0;
                    }


                    /* CHECK WRITING ENTRY */
                    if ($writingEntry) 
                    {

                        if ($hasAttachment == 'on' || $hasAttachment == true) 
                        {
                            if ($is_appointed == 'on') {
                                $totalDeduction = $wordPointDeduction * 2;
                            } else {
                                $totalDeduction = $wordPointDeduction;
                            }                               
                        } else {
                            $totalDeduction = $wordPointDeduction;
                        }


                        $deposit = $writingEntry->total_points;
                        $totalCredits = $credits + $deposit;
                        $totalPointsLeft = $totalCredits - $totalDeduction;

                        if ($totalPointsLeft < 0) 
                        {
                            $reload = abs($totalPointsLeft);
                            $success =  false;
                            $message =  "Sorry, the member don't have enough $point_type for this entry.";
                            $message .= "<div>This entry requires $reload  addtional point(s), Member only have $credits credit(s) and $deposit point deposit  </div>";

                        } else {
                            $reload  = 0;
                            $success =  true;
                            $message = "<div>Congratulations, Member have sufficient $point_type </div>";
                        }

                        return Response()->json([
                            "success"   => $success,                 
                            "message"   => $message, 
                            "credit"    => $credits,
                            "deposit"   => $deposit,
                            "wordPointDeduction" => $wordPointDeduction,
                            "totalDeduction" => $totalDeduction,
                            "reload"    => $reload 
                        ]);

                    } else {
                        //The user did not have a valid entry
                        return Response()->json([
                            "success"           => false,                 
                            "message"           => "Member has no valid writing entry",                    
                        ]);
                    }
                }

            } else {
                //The user account is a tutor
                return Response()->json([
                    "success"           => false,                 
                    "message"           => "User is not a member , you are a tutor please go to tutor page",
                ]);
            }                
            
            
        }        

        
    }


    public function sendReloadEmail(Request $request, Member $member) 
    {        
        try {
        
            //send the authenticated user the email, since the Authenticated user
            $user = User::find($request->memberID);

            //E-Mail Template
            $emailTemplate = 'emails.writing.sendMemberReloadReminder';           

            //E-Mail Recipient
            $emailTo['name'] = $user->firstname ." ". $user->lastname;
            $emailTo['email'] = $user->email; 

            //Email Reply To
            $emailFrom['name']   = Config::get('mail.from.name');
            $emailFrom['email']  = Config::get('mail.from.address');

            $emailSubject = '矯正サービス'; //Information on correction service reception
            $emailMessage = $request->reload;

            $job = new \App\Jobs\SendAutoReplyJob($emailTo, $emailFrom, $emailSubject, $emailMessage, $emailTemplate);
            dispatch($job);                    

            return Response()->json([
                "success"  => true,   
                "message"  => "E-Mail Sent"
            ]); 

         } catch (\Exception $e) {

            return Response()->json([
                "success"  => false,   
                "message"  => "Error while E-Mail Sent ". $e>getMessage()
            ]); 
         }
 
    
    }
}