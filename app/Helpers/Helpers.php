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

if (! function_exists('formatGrade')) {
    function formatGrade($grade) {
       $str = str_replace('_', ' ', $grade);
       $str = explode(" ", $str);
       $grade = $str[0] ." ". $str[1]. "-" . $str[2] ."%";
       return $grade;
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



?>