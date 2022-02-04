<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notes extends Model
{
    public $table = 'member_notes';

    function getMemberNotes($memberID) 
    {        
        Notes::where('valid', true)->where('member_id', $memberID)->get();    
    }



    
}
