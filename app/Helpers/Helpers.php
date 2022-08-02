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

//normal japanese date only formatter 
if (! function_exists('JapaneseDateFormat')) {
    function JapaneseDateFormat($date) 
    {        
        return  date('Y年 m月 d日', strtotime($date));
    }
}



if (! function_exists('LessonTimeRange')) {
    function LessonTimeRange($lessonTime) {
        if (date('H', strtotime($lessonTime)) == '00') {
            return date('Y年 m月 d日 24:i', strtotime($lessonTime ." - 1 day"))  ."-".   date('24:i', strtotime($lessonTime." + 25 minutes "));
        } else {
            return date('Y年 m月 d日 H:i', strtotime($lessonTime))  ."-".   date('H:i', strtotime($lessonTime." + 25 minutes "));
        }
    }
}
  

//japanese date only formatter
if (! function_exists('ESIDateFormat')) {
    function ESIDateFormat($date) 
    {        
        if (date('H', strtotime($date)) == '00') 
        {  
           return date('Y年 m月 d日', strtotime($date ." - 1 day"));
        } else {
            return  date('Y年 m月 d日', strtotime($date));
        }
    }
}

//japanese date time formatter
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

//japanese date time with seconds formatter
if (! function_exists('ESIDateTimeSecondsFormat')) {
    function ESIDateTimeSecondsFormat($date) 
    {        
        if (date('H', strtotime($date)) == '00') 
        {  
           return date('Y年 m月 d日 24:i:s', strtotime($date ." - 1 day"));
        } else {
            return  date('Y年 m月 d日 H:i:s', strtotime($date));
        }
    }
}

//Standard Date format
if (! function_exists('ESIDate')) {
    function ESIDate($date) 
    {        
        if (date('H', strtotime($date)) == '00') 
        {  
            return date('F d, Y', strtotime($date ." - 1 day")) ;
        } else {
            return  date('F d, Y', strtotime($date));
        }
    }
}


//english ESI date formatter
if (! function_exists('ESIDateTimeENFormat')) {
    function ESIDateTimeENFormat($date) 
    {        
        if (date('H', strtotime($date)) == '00') 
        {  
           return date('Y, m-d 24:i', strtotime($date ." - 1 day"));
        } else {
            return  date('Y, m-d H:i', strtotime($date));
        }
    }
}


//english date time with seconds formatter
if (! function_exists('ESIDateTimeSecondsENFormat')) {
    function ESIDateTimeSecondsENFormat($date) 
    {        
        if (date('H', strtotime($date)) == '00') 
        {  
           return date('Y, m-d 24:i:s', strtotime($date ." - 1 day"));
        } else {
            return  date('Y, m-d H:i:s', strtotime($date));
        }
    }
}



//Mailer Date EN format
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

//Lesson Time format
if (! function_exists('ESILessonTimeENFormat')) {
    function ESILessonTimeENFormat($date) 
    {        
        if (date('H', strtotime($date)) == '00') 
        {  
           return date('Y-m-d 24:i', strtotime($date ." - 1 day"));
        } else {
            return  date('Y-m-d H:i', strtotime($date));
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

if (! function_exists('ESILessonTimeRange')) {
    function ESILessonTimeRange($time) 
    {   
        if (date('H', strtotime($time)) == '00') 
        {  
            return date('24:i', strtotime($time)) ." - " . date('24:i', strtotime($time . " +25 minutes"));
        } else {
            return date('H:i', strtotime($time)) ." - ". date('H:i', strtotime($time . " +25 minutes"));
        }
    }
}
    

if (! function_exists('getMonths')) {
    function getMonths($maxCount) 
    {         
        for($i=1; $i <= $maxCount; $i++) 
        {
            if ($i == 1) {
                $months[] = date("Y-m");
            } else {
                $months[] = date("Y-m", strtotime("-$i month"));
            }            
        }
        return $months;
    }
}

if (! function_exists('getMonthAndYear')) {
    function getMonthAndYear($maxCount) 
    {         
        for($i=1; $i <= $maxCount; $i++) 
        {
            if ($i == 1) {
                $monthsYears[] = date("Y-m");
            } else {
                $monthsYears[] = date("Y-m", strtotime("-$i month"));
            }            
        }
        return $monthsYears;
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

if (! function_exists('formatWords')) {
    function formatWords($string) {
       $fstr = str_replace('_', ' ', $string);
       $spaced_fstr = preg_replace('/(?<! )(?<!^)(?<![A-Z])[A-Z]/', ' $0', $fstr);
       return ucwords($spaced_fstr);
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

if (! function_exists('checkbox_ticker')) {
    
    function checkbox_ticker($field_name, $value)
    {
        if( is_array( $field_name ) && in_array($value, $field_name )) {
            return " checked ";
        } else if (isset($field_name) && isset($value)){

            if ($field_name == $value ) {
                return " checked ";
            }

        }
    } 
}


if (! function_exists('countWords')) 
{
    function countWords($text) {
        return str_word_count($text);
    }  
}


if (! function_exists('minutesFormatter')) 
{
    function minutesFormatter($minutes) {
        $d = floor ($minutes / 1440);
        $h = floor (($minutes - $d * 1440) / 60);
        $m = $minutes - ($d * 1440) - ($h * 60);
        return "{$d}d {$h}h {$m}m";
    }
}


if (! function_exists('HoursToTime')) 
{
    function HoursToTime($dec) {
        // start by converting to seconds
        $seconds = ($dec * 3600);
        // we're given hours, so let's get those the easy way
        $hours = floor($dec);
        // since we've "calculated" hours, let's remove them from the seconds variable
        $seconds -= $hours * 3600;
        // calculate minutes left
        $minutes = floor($seconds / 60);
        
        // remove those from seconds as well
        $seconds -= $minutes * 60;


        return lz($hours)."h ".lz($minutes)."m ";
    }
}

if (! function_exists('lz')) 
{
    // lz = leading zero
    function lz($num)
    {
        return (strlen($num) < 2) ? "0{$num}" : $num;
    }
}

if (! function_exists('calculateDecimalHours')) 
{
    function calculateDecimalHours($dec) {
        // start by converting to seconds
        $seconds = ($dec * 3600);
        // we're given hours, so let's get those the easy way
        $hours = floor($dec);
        // since we've "calculated" hours, let's remove them from the seconds variable
        $seconds -= $hours * 3600;
        // calculate minutes left
        $minutes = floor($seconds / 60);
        
        // remove those from seconds as well
        $seconds -= $minutes * 60;

        return lz($hours).".".lz($minutes);
    }
}

if (! function_exists('calculateHoursToDays')) 
{
    function calculateHoursToDays($minutes) {
        $days = $minutes / 24;
        return $days;
    }  
}


if (! function_exists('calculateDaysToHours')) 
{
    function calculateDaysToHours($days) {
        $hours = $days * 24;
        return $hours;
    }  
}

if (! function_exists('calculateHoursToMinutes')) 
{
    function calculateHoursToMinutes($hours) {
        $minutes = $hours * 60;
        return $minutes;
    }  
}


if (! function_exists('calculateMinutesToHours')) 
{
    function calculateMinutesToHours($minutes) {
        $hours = ($minutes / 60);
        return  $hours;
    }  
}



if (! function_exists('addSpaceBeforeCapitalizedLetters')) 
{
    function addSpaceBeforeCapitalizedLetters($String) {
      $string =  preg_replace('/(?<!\ )[A-Z]/', ' $0', $String);
      return ucfirst($string);
    }  
}


if (! function_exists('getRemainingDays')) 
{
    function getRemainingDays($start, $end) 
    {

        $timeleft = strtotime(ESIDate($end)) - strtotime(ESIDate($start));
        $daysleft = round((($timeleft/24)/60)/60); 
        return $daysleft;
    }  
}


/*********************************
    Database Time formatting
/********************************* */

//2022-03-22

if (! function_exists('mysql_format_date')) 
{
    function mysql_format_date($date) 
    {        
        return  date('Y-m-d', strtotime($date));
    }
}



if (! function_exists('mysql_format_datetime')) 
{
    function mysql_format_datetime($date) 
    {        
        return  date('Y-m-d H:m:s', strtotime($date));
    }
}



?>