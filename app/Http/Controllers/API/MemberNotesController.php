<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Notes;

class MemberNotesController extends Controller
{
    function getMemberNotes(Request $request) 
    {

        $memberID = $request->get('memberID');

        
    

    }
}
