<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\LessonMaterial;
use App\Models\CourseCategory;
use Illuminate\Http\Request;
use DB;

class LessonMaterialsController extends Controller
{
    

    public function sortLessonMaterials(Request $request) 
    {
        try {
            DB::beginTransaction();

            $sort = 1;
            $lessonMaterialObj = new LessonMaterial();
    
            foreach($request->material as $material) 
            {

                $data = [
                    'sort_order' => $sort
                ];
                
                $lessonMaterial = $lessonMaterialObj->find($material);
                $lessonMaterial->update($data);        
                DB::commit();

                $sort = $sort + 1;
            }
    
            return Response()->json([
                "success" => true,
                "message" => "Lesson Materials has been re-ordered successfully."
            ]); 
        }  catch (\Exception $e) {

            return Response()->json([
                "success" => false,
                "message" => "Exception Found " . $e->getMessage() . " on Line " . $e->getLine(),
            ]);

            DB::rollback();
        }
  

    }
}
