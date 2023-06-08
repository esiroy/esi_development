<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Auth;

class Notes extends Model
{
    public $table = 'member_notes';
    

    protected $guarded = array('created_at', 'updated_at');


    function getMemberNotes($memberID) 
    {        
        return Notes::select('member_notes.id as note_id', 'member_notes.tutor_id', 'member_notes.member_id', 'member_notes.note as note', 'member_notes.updated_at',
                        //'user_image.original as tutor_photo',  
                        'users.firstname as tutor_name'
                    )
                    //->join('user_image', 'user_image.user_id', '=', 'member_notes.tutor_id')
                    ->join('users', 'users.id', '=', 'member_notes.tutor_id')
                    ->where('member_notes.member_id', $memberID)
                    ->where('member_notes.valid', true)
                    ->orderBy('member_notes.created_at', 'DESC')
                    ->paginate(Auth::user()->items_per_page);
    }

    function saveMemberNote($memberID, $tutorID, $note) 
    {
    
        return Notes::create([            
            'member_id' => $memberID,
            'tutor_id' => $tutorID,
            'note' => $note,
            'valid' => true,
        ]);

    }

    function updateMemberNote($noteID, $memberID, $tutorID, $note) 
    {

        $notes = new Notes();

        $noteEditedFound = $notes->find($noteID);

        if ($noteEditedFound) 
        {
            return $noteEditedFound->update([            
                //'member_id' => $memberID,
                //'tutor_id' => $tutorID,
                'note' => $note,
                'valid' => true,
            ]);        
        }
    }
    
}
