<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberLevel extends Model
{
    public $table = 'member_level';

    protected $guarded = array('created_at', 'updated_at');

    function getLevel($memberID) 
    {
        return MemberLevel::where('member_id', $memberID)->where('valid', true)->first();    
    }


    function saveLevel($data) 
    {
        $memberLevel = MemberLevel::where('member_id', $data['memberID'])->where('valid', true)->first();


        if (isset($data['level']) && isset($data['memberID'])) {

            if ($memberLevel) 
            {
                $data = [                        
                        'level' => $data['level'],
                        'description'    => $data['description'],
                    ];
                return $memberLevel->update($data);
            } else {
            
                $data = [
                        'member_id'     =>  $data['memberID'],
                        'level'         => $data['level'],
                        'description'    => $data['description'],
                        'valid'         => true,
                ];

                return MemberLevel::create($data);
            }
         }

    }

}
