<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\MiniTestResult;


class MemberMiniTestResultController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function get(Request $request, MiniTestResult $miniTestResult)
    {        
        try {           

            $memberID = $request->get('memberID');

            $items = $miniTestResult->getResults($memberID);

            if ($items) 
            {
                return Response()->json([
                    "success"       => true,
                    "message"       => "Member Test Result has been fetch successfully",
                    "items"         => $items,
                ]);
            }

        } catch (\Exception $e) {

            return Response()->json([
                "success" => false,
                "message" => "Getting Member Test Result failed " . $e->getMessage() . " on Line : " . $e->getLine(),
            ]);                        
        }
    }

  
    public function destroy($id)
    {

        try {    

            $note = MiniTestResult::find($id);

            $note->update([
                'valid' => false,
            ]);

            return Response()->json([
                "success" => true,
                "message" => "Mini-test result has been deleted successfully",
            ]);

         } catch (\Exception $e) {
            return Response()->json([
                "success" => false,
                "message" => "Deleting Mini-test result failed " . $e->getMessage() . " on Line : " . $e->getLine(),
            ]);  
         }
    }
}
