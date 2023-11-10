<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $guarded = array('id', 'created_at', 'updated_at');


    function getGrade($rating) {

        if (!($rating)) return null;

        switch ($rating) {
            case 1:
                $grade = 'UNDERSTAND_0_19';
                break;
            case 2:
                $grade = 'UNDERSTAND_20_40';
                break;
            case 3:
                $grade = 'UNDERSTAND_41_64';
                break;
            case 4:
                $grade = 'UNDERSTAND_65_85';
                break;
            case 5:
                $grade = 'UNDERSTAND_86_100';
                break;
            default:
                $grade = 'null;';
        }

        return $grade;
    }
}
