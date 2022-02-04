<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notes extends Model
{
    public $table = 'member_notes';

    function getMemberNotes($memberID) 
    {        
        return Notes::select('member_notes.*', 'user_image.original as tutorPhoto')
                    ->leftJoin('user_image', 'user_image.user_id', '=', 'member_notes.tutor_id')
                    ->where('member_notes.member_id', $memberID)
                    ->where('member_notes.valid', true)
                    ->orderBy('member_notes.created_at', 'DESC')
                    ->get();
    }



    
}
