<?php
/* 
Helpers functions 

Autoload this in composer.json file,

"autoload": {
    "files": [
        "app/helpers.php"
    ],
    ...
*/

use Illuminate\Support\Str;

if (! function_exists('ESIDateFormat')) {
    function ESIDateFormat($date) 
    {        
        return  date('Y年 m月 d日 ', strtotime($date));
    }
}

if (! function_exists('ESIDateTimeFormat')) {
    function ESIDateTimeFormat($date) 
    {        
        if (date('H', strtotime($date)) == '00') 
        {  
           return date('Y年 m月 d日 24:i', strtotime($date ." - 1 day"));
        } else {
            return  date('Y年 m月 d日 H:i', strtotime($date));
        }
    }
}

if (! function_exists('ESIMailDateTimeFormat')) {
    function ESIMailDateTimeFormat($date) 
    {        
        if (date('H', strtotime($date)) == '00') 
        {  
            return date('F j, Y, 24:i', strtotime($date ." - 1 day"));
        } else {
            return  date('F j, Y, H:i', strtotime($date));
        }
    }
}

if (! function_exists('ESIFormatTime')) {
    function ESIFormatTime($time) 
    {        
        if (date('H', strtotime($time)) == '00') 
        {  
            return date('24:i', strtotime($time ." - 1 day")) ;
        } else {
            return  date('H:i', strtotime($time));
        }
    }
}



if (! function_exists('limit')) {
    function limit($string, $number) {
        $truncated = Str::of($string)->limit($number, ' (...)');
        return $truncated;
    }
}


if (! function_exists('sortByDate')) {    
    function sortByDate($key)
    {
        return function ($a, $b) use ($key) {
            $t1 = strtotime($a[$key]);
            $t2 = strtotime($b[$key]);
            return $t2-$t1;
        };

    }
}

if (! function_exists('formatGrade')) {
    function formatGrade($grade) {
       $str = str_replace('_', ' ', $grade);
       $str = explode(" ", $str);
       $grade = $str[0] ." ". $str[1]. "-" . $str[2] ."%";
       return $grade;
    }
}



if (! function_exists('formatStatus')) {
    function formatStatus($status) {
       $str = str_replace('_', ' ', strtolower($status));
       return ucwords($str);
    }
}


if (! function_exists('createAttributes')) {
    function createAttributes() {
        $attributes = [
            [
                'id' => 1,                
                'name' => "Trial",
                'value' => "TRIAL"
            ],
            [
                'id' => 2,
                'name' => "Member",
                'value' => "MEMBER"
            ],
            [
                'id' => 3,
                'name' => "Withdraw",
                'value' => "WITHDRAW"
            ],
        ];
        return $attributes;
    }
}

if (! function_exists('createMembership')) {
    function createMembership() {
        $memberships = [
            [
                'id' => 1,
                'name' => "Point Balance",
            ],
            [
                'id' => 2,
                'name' => "Monthly",
            ],
            [
                'id' => 3,
                'name' => "Both",
            ],
        ];
        return $memberships;
    }
}

if (! function_exists('createGrades')) {
    function createGrades() {
        $tutorGrades = [
            [
                'id' => 1,
                'name' => "Standard",
            ],
            [
                'id' => 2,
                'name' => "Upgrade",
            ],
            [
                'id' => 3,
                'name' => "Platinum",
            ],
        ];
        return $tutorGrades;
    }
}


if (! function_exists('createIndustries')) {
    function createIndustries() {
        $industries = [
            [
                'id' => 1,
                'name' => "Private School",
                'value' => "PRIVATE_SCHOOL"
            ],
            [
                'id' => 2,
                'name' => "Public School",
                'value' => "PUBLIC_SCHOOL",
            ],
            [
                'id' => 3,
                'name' => "Company",
                'value' => "COMPANY",                
            ],
            [
                'id' => 4,
                'name' => "Individual",
                'value' => "INDIVIDUAL",                
            ],            
        ];
        return $industries;
    }
}



if (! function_exists('getQuestionnnaireGrade')) {
    function getQuestionnnaireGradeTranslation($grade) {
        if ($grade == "GOOD") {
            return "良かった";
        } else if ($grade == "AVERAGE") {
            return "まあまあ";
        } else if ($grade == "BAD") {
            return "良くなかった";
        } else {
            return "-";
        }
    };
}


if (! function_exists('unique_multidim_array')) 
{
    function unique_multidim_array($array, $key) {
        $temp_array = array();
        $i = 0;
        $key_array = array();
    
        foreach($array as $val) {
            if (!in_array($val[$key], $key_array)) {
                $key_array[$i] = $val[$key];
                $temp_array[$i] = $val;
            }
            $i++;
        }
        return $temp_array;
    }
}


if (! function_exists('linkify')) 
{
    function linkify($string) {
        $string = preg_replace(
            "~[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]~",
            "<a href=\"\\0\" class='blue' target='_blank'>\\0</a>", 
            $string);
        return $string;
    }
}


?>