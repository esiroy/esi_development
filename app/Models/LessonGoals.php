<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LessonGoals extends Model
{
    public $table = 'lesson_goals';

    protected $guarded = array('created_at', 'updated_at');

    
    /** 
     * Get Lesson Goals for Member
     * @param - $memberID - required
     * @return $lessonGoals ($purposeDescription, $goalDescription)     
     * */
    public function getLessonGoals($memberID)
    {        
        $lessonGoals = LessonGoals::where('member_id', $memberID)->where('valid', 1)->orderBy('id', 'ASC')->get();
        foreach ($lessonGoals as $key => $goal) {
            $lessonGoals[$key]['purposeDescription'] = LessonGoals::getPurposeDescription($goal->purpose);
            $lessonGoals[$key]['goalDescription'] = LessonGoals::getGoalDescription($goal->purpose, $goal->goal);
        }
        return $lessonGoals;
    }

    public function getPurposeDescription($purpose)
    {

        switch ($purpose) {
            case "BILINGUAL":
                $purposeDescription = "Take part in Bilingual training course";
                break;
            case "CONVERSATION":
                $purposeDescription = "Get conversation(communication) skill";
                break;
            case "ANTI_EIKEN":
                $purposeDescription = "English certification exam in Japan";
                break;
            case "ANTI_EXAM":
                $purposeDescription = "Enter school";
                break;
            case "TOEFL":
                $purposeDescription = "TOEFL(目標スコアー 点)";
                break;
            case "TOEIC":
                $purposeDescription = "TOEIC(目標スコアー 点)";
                break;
            case "STUDY_ABROAD":
                $purposeDescription = "Study Abroad";
                break;
            case "BUSINESS":
                $purposeDescription = "Business English";
                break;
            case "OTHERS":
                $purposeDescription = "Others";
                break;
            default:
                $purposeDescription = null;
        }
        return $purposeDescription;
    }

    public function getGoalDescription($purpose, $goal)
    {
        if ($purpose == "CONVERSATION") {

            switch ($goal) {
                case "BEGINNER":
                    $goalDescription = "Beginner- easy daily conversation level";
                    break;
                case "INTERMEDIATE":
                    $goalDescription = "Intermediate- Daily conversation level";
                    break;
                case "ADVANCE";
                    $goalDescription = "Advance - Social, Environment, Business English";
                    break;
                case "NATIVE";
                    $goalDescription = "Be native level";
                    break;
                default:
                    $goalDescription = null;
            }
        } else {
            $goalDescription = null;
        }

        return $goalDescription;
    }
}
