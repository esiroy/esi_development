<?php

namespace Tests\Unit;

//use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use App\Models\LessonSlideHistory;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LessonSlideHistoryTest extends TestCase
{


    /**
     * A basic unit test example.
     *
     * @return void
     */

   


    public function testExample()
    {




        $lessonHistory = (object) [
                'lesson_history_id' => 1,
                'slide_index'       => 1,
                'content'           => "just a test" 
        ];

        $lessonSlideHistory = new LessonSlideHistory();
        $response = $lessonSlideHistory->saveSlideHistory($lessonHistory);

        if ($response->success == true) {
            $this->assertTrue(true);
        } else {
            $this->assertTrue(false);
        }

        
    }
}
