<?php

namespace Tests\Unit;


use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Controllers\Admin\Modules\WritingController;

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Log;




use App\Models\WritingEntries;

class WritingControllerTest extends TestCase
{


    public function testOnePointDeduction() 
    {    
        //1 points total (180 words), additonal is 0 since it will just take the credit
        $writingCredit = 1;
        $words = 180;

        $writingEntries = new WritingEntries();
        $additionalPoints = $writingEntries->calculateAddtionalPoint($writingCredit, $words);
        $this->assertEquals(0 , $additionalPoints);
    }
  
    public function testOnePointTotalDeductedPoints() 
    {    
        $writingCredit = 1;
        $words = 180;        
        $writingEntries = new WritingEntries();
        $totalDeductted = $writingEntries->totalDeductedPoints($writingCredit, $words, $appointed = false);
        $this->assertEquals(1 , $totalDeductted);
    }



    public function testTwoPointsDeduction() 
    {
        //2 points total (253 words), additonal is 1 and take the 1 writing credit
        $writingCredit = 1;
        $words = 500;  

        $writingEntries = new WritingEntries();
        $additionalPoints = $writingEntries->calculateAddtionalPoint($writingCredit,  $words);
        $this->assertEquals(1 , $additionalPoints);
    }

  
      public function testTwoPointsTotalDeductedPointsWithAttachments() {   
        //2 points x 2 = 4 points total
        $appointed  = true;
        $writingCredit = 2;
        $words = 500;        
        $writingEntries = new WritingEntries();
        $totalDeducted = $writingEntries->totalDeductedPoints($writingCredit, $words, $appointed);
        if ($appointed == true) {        
            $this->assertEquals(4 , $totalDeducted);
        } 
    }

    public function testTwoPointsTotalDeductedPoints() 
    {    
        $writingCredit = 1;
        $words = 500;        
        $writingEntries = new WritingEntries();
        $totalDeducted = $writingEntries->totalDeductedPoints($writingCredit, $words, $appointed = false);
         $this->assertEquals(2 , $totalDeducted);
     
    }


    public function testThreePointDeduction() 
    {
        //3 points total (500 words), additonal is 2 and take the 1 writing credit
        $writingCredit = 1;
        $words = 501;  

        $writingEntries = new WritingEntries();
        $additionalPoints = $writingEntries->calculateAddtionalPoint($writingCredit,  $words);
        $this->assertEquals(2 , $additionalPoints);
    }

    public function testThreePointsTotalDeductedPoints() 
    {    
        $writingCredit = 1;
        $words = 501;        
        $writingEntries = new WritingEntries();
        $totalDeductted = $writingEntries->totalDeductedPoints($writingCredit, $words, $appointed = false);
        $this->assertEquals(3 , $totalDeductted);
    }


    /*
    public function testCreateTutorWritingReply()
    {
    
        $id = 28;

        $data = [
            "_token" => csrf_token(),
            "course" => "1",
            "appointed_value" => "on",
            "material" => "1",
            "words" => "253",
            "hasAttachment" => true,
            "subject" => "1",
            "grade" => "1",
            "content" => "1sdfasdf"
        ];
        
        $dataObj = json_decode(json_encode($data));     

        $writingEntriesClass = new WritingEntries();
        $result = $writingEntriesClass->getAdditionalDeductionForAttachment($id, $dataObj);

        // Arrange: Prepare the test data and environment
        
        // Act: Perform the authentication process
        
        $this->assertEquals(1 , $result['amount']);

        // Assert: Check if the user is authenticated
       // $this->assertEquals(1 , $result['amount']);

       
    }
    */
}
