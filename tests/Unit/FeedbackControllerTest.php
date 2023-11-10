<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

use PHPUnit\Framework\TestCase;


class FeedbackControllerTest extends TestCase
{

    public function testFeedbackStore()
    {
        $data = [
            'key' => 'value',
            // Add other form input data here
        ];


        //reservation schedule id
        $scheduleID     = $request->reservation['schedule_id'];
        $tutorID        = $request->reservation['tutor_id'];
        $memberID       = $request->reservation['member_id'];
        $rating         = $request->studentPerformanceRating;
        $material       = json_decode($request->material);
        $lessonStatus   = $request->lessonStatus;
        $lessonGrade    = $grade->getGrade($rating);
        $feedback       = $request->feedback;  
        $memberNotes    = $request->notes;    



        $response = $this->post('/api/postMemberFeedback', $data);

        $response->assertStatus(302); // Redirect after form submission
        $this->assertDatabaseHas('my_table', ['key' => 'value']); // Check if data was saved to the database
    }

    // Add more test methods for other controller actions as needed
}
