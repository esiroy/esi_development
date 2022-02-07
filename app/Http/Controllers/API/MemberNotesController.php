<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Notes;

class MemberNotesController extends Controller
{
    function getMemberNotes(Request $request, Notes $notes) 
    {
        try {           
            $memberID = $request->get('memberID');
            $notes = $notes->getMemberNotes($memberID);
            return Response()->json([
                "success"       => true,
                "message"       => "Member Notes has been fetch successfully",
                "notes"         => $notes,
            ]);

        } catch (\Exception $e) {
            return Response()->json([
                "success" => false,
                "message" => "Getting Member notes failed " . $e->getMessage() . " on Line : " . $e->getLine(),
            ]);                        
        }
    }


    function saveNote(Request $request, Notes $notes) 
    {    

        try {   
            $memberID = $request->get('memberID');   
            $tutorID = $request->get('tutorID');     
            $note   = $request->get('note');

            $response = $notes->saveMemberNote($memberID, $tutorID, $note);

            return Response()->json([
                "success" => true,
                "message" => "Member Notes has been fetch successfully",
                "note"   =>  $response
            ]);
       } catch (\Exception $e) {
            return Response()->json([
                "success" => false,
                "message" => "Saving Member notes failed " . $e->getMessage() . " on Line : " . $e->getLine(),
            ]);                        
        }
    }



    function updateNote(Request $request, Notes $notes) 
    {
        try {   

            $noteID =  $request->get('noteID');
            $memberID = $request->get('memberID');   
            $tutorID = $request->get('tutorID');     
            $note   = $request->get('note');

            $notes->updateMemberNote($noteID, $memberID, $tutorID, $note);

            return Response()->json([
                "success" => true,
                "message" => "Member Notes has been fetch successfully",
            ]);

       } catch (\Exception $e) {
            return Response()->json([
                "success" => false,
                "message" => "Saving Member notes failed " . $e->getMessage() . " on Line : " . $e->getLine(),
            ]);                        
        }
    }


    function deleteNote(Request $request, Notes $notes) 
    {

        try {    

            $note = Notes::find($request->get('noteID'));

            $note->update([
                'valid' => false,
            ]);

            return Response()->json([
                "success" => true,
                "message" => "Member Notes has been deleted successfully",
            ]);

         } catch (\Exception $e) {
            return Response()->json([
                "success" => false,
                "message" => "Deleting Member notes failed " . $e->getMessage() . " on Line : " . $e->getLine(),
            ]);  
         }

    }
}
