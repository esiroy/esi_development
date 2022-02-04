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
            return Response()->json([
                "success" => true,
                "message" => "Member Notes has been fetch successfully",
                "notes"   => $notes->getMemberNotes($memberID)
            ]);
        } catch (\Exception $e) {
            return Response()->json([
                "success" => false,
                "message" => "Getting Member notes failed " . $e->getMessage() . " on Line : " . $e->getLine(),
            ]);                        
        }
    }


    function saveNote(Request $request) 
    {    
        $memberID = $request->get('memberID');         
    }
}
