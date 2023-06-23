<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Storage;


class Tutor extends Model
{
    public $table = 'tutors';

    protected $guarded = array('created_at', 'updated_at');

    public $timestamps = false;    

    public function setBirthdateAttribute($value)
    {       
        $this->attributes['birthdate'] = date('Y-m-d', strtotime($value)); 
    }
    
    public function user() 
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    /*
        @shift default 4 = 25 mintues
    */
    public function getTutors($shift = 4) {
       //Updated: Remove terminated tutor on the list
       $tutors = Tutor::where('lesson_shift_id', $shift)
       ->where('is_terminated', 0)
       ->join('users', 'users.id', '=', 'tutors.user_id')
       ->orderBy('sort', 'ASC')
       ->select('tutors.*', 'users.firstname', 'users.lastname', 'users.valid')
       ->where('valid', 1)
       ->get();
       return $tutors;
    }

    public function getActiveTutors($shift = 4) {
       //Updated: Remove terminated tutor on the list
       $tutors = Tutor::where('lesson_shift_id', $shift)       
       ->join('users', 'users.id', '=', 'tutors.user_id')
       ->orderBy('sort', 'ASC')
       ->select('tutors.*', 'users.firstname', 'users.lastname', 'users.valid')
        ->where('users.valid', 1)
        ->where('tutors.is_terminated', false)
        ->get();

       return $tutors;
    }




    public function getTutorByID($id, $shift = 4) {
       //Updated: Remove terminated tutor on the list
       $tutors = Tutor::where('lesson_shift_id', $shift)
       ->where('is_terminated', 0)
       ->join('users', 'users.id', '=', 'tutors.user_id')
       ->orderBy('sort', 'ASC')
       ->select('tutors.*', 'users.firstname', 'users.lastname', 'users.valid')
       ->where('tutors.user_id', $id)
       ->where('valid', 1)
       ->get();
       return $tutors;
    }

    public function image() 
    {        
        $userImage = UserImage::where('user_id', $this->user_id)->where('valid', 1)->first();

        if ($userImage) {
            return  Storage::url($userImage['original']);
        } else {
            return null;
        }       
     
    }    
}
