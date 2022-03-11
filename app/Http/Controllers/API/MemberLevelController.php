<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\MemberLevel;

class MemberLevelController extends Controller
{

    function getMemberLevel(Request $request, MemberLevel $memberLevel) 
    {

        $level =  $memberLevel->getLevel($request['memberID']);

        if ($level) {
            
            return Response()->json([
                "success"       => true,
                "message"       => "Member level has been fetch successfully",
                "level"         => $level,
            ]);

        } else {
        
            return Response()->json([
                "success"       => false,
                "message"       => "Member has not submitted any CESFR Level",
                "level"         => $level,
            ]);       

        }
    }

    function saveMemberLevel(Request $request, MemberLevel $memberLevel) 
    {
      
        try {

             if (isset($request['level']) && isset($request['memberID'])) {

                $data = [
                            'memberID'  => $request['memberID'],
                            'level'     => $request['level'],
                            'description' => $request['description']
                        ];

                $response = $memberLevel->saveLevel($data);

                return Response()->json([
                    "success" => true,
                    "message" => "Member level has been added successfully",
                    "level"   =>  $request['level']
                ]);

             }


        } catch (\Exception $e) {

            return Response()->json([
                "success" => false,
                "message" => "Saving Member level failed " . $e->getMessage() . " on Line : " . $e->getLine(),
            ]);  
        }


    }
}
