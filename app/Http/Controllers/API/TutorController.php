<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Shift;
use App\Models\Tutor;



class TutorController extends Controller
{
    public function getTutors(Request $request, User $user)
    {
        //$shifts = User::where('shift_id', $request['shift']);

        //@do: get shift id then query on tutors for that id, then loop?
        //$shift  = Shift::find($request['shift'])->first();

        $tutors = Tutor::where('shift_id', $request['shift_id'])->get(); 
        return Response()->json([
            "success" => true,  
            "tutors" => $tutors,            
        ]);  
    }


}
