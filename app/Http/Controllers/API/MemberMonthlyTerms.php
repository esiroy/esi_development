<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MemberMonthlyTerm;

class MemberMonthlyTerms extends Controller
{
   
    public function agree(Request $request, MemberMonthlyTerm $memberMonthlyTerm)
    {
        $memberMonthlyTerm->agreeMonthlyTerm($request->memberID);
        
        return Response()->json([
            "success"       => true,
            "name"          => "agree_monthly_terms",
            "message"       => "monthly term agreed"            
        ]);
    }

}
