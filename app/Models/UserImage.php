<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserImage extends Model
{
    public $table = 'user_image';

    protected $guarded = array('created_at', 'updated_at');

    /**
     * @param member - object of member 
     */
    public function getMemberPhoto($member) {
        if (isset( $member->user_id)) {
            $userImage = UserImage::where('user_id', $member->user_id)->where('valid', 1)->first();
        } else {
            $userImage = null;
        }
        return $userImage;
    }

    /**
     * @param tutorID - id of tutor 
     */    
    public function getTutorPhotobyID($tutorID) 
    {        
        $userImage = UserImage::where('user_id', $tutorID)->where('valid', 1)->first();

        if ($userImage) {
            return $userImage;
        } else {
            return null;
        }       
        return $userImage;
    }
}
