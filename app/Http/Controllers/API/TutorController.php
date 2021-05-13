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
     
        /*
        $tutors = Tutor::where('lesson_shift_id', $request['shift_id'])
        //->where('is_terminated', '!=', 1)
        ->orWhere('is_terminated', '=', null) //@todo: confirm null is not terminated
        ->join('users', 'users.id', '=', 'tutors.user_id')
        ->select('tutors.*','users.firstname', 'users.lastname')->get();
        */

        //Update: Remove terminated tutor on the list
        $tutors = Tutor::where('lesson_shift_id', $request['shift_id'])
        ->where('is_terminated', 0)
        ->join('users', 'users.id', '=', 'tutors.user_id')
        ->orderBy('sort', 'ASC')
        ->select('tutors.*', 'users.firstname', 'users.lastname', 'users.valid')
        ->where('valid', 1)
        ->get();

        return Response()->json([
            "success" => true,  
            "tutors" => $tutors,            
        ]);  
    }
}