<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Tutor;
use Auth;

class TutorProfileController extends Controller
{
    public function show($id) 
    {
        //$tutor = Tutor::where('user_id', $id)->first();


        $tutor = Tutor::where('tutors.user_id', $id)->where('valid', 1)->where('is_terminated', 0)->join('user_image', 'user_image.user_id', '=', 'tutors.user_id')->first();
        
        
        if (isset($tutor)) {

            return view('modules/tutor/show', compact('tutor'));

        } else {                   

            abort(404);           
        }
    }
}
