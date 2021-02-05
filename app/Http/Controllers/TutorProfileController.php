<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Tutor;
use Auth;

class TutorProfileController extends Controller
{
    public function show($id) 
    {
        
        //$tutor = Tutor::where('tutors.user_id', $id)->where('valid', 1)->where('is_terminated', 0)->first();

        $tutor = Tutor::where('tutors.user_id', $id)
        ->where('tutors.is_terminated', 0)
        ->where('users.valid', 1)
        //->orWhere('is_terminated', '=', null) //@todo: confirm null is not terminated
        ->join('users', 'users.id', '=', 'tutors.user_id')
        ->leftjoin('user_image', 'user_image.user_id', '=', 'tutors.user_id')
        //->orderBy('firstname', 'ASC')
        ->orderBy('sort', 'ASC')
        ->select('tutors.*', 'users.firstname', 'users.lastname', 'users.valid', 'user_image.filename', 'user_image.original')        
        ->first();
                     
        
        
        
        if (isset($tutor)) {

            return view('modules/tutor/show', compact('tutor'));

        } else {                   

            abort(404);           
        }
    }
}
