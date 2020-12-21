<!--latest report cards-->

 <div class="profile blueBox pt-0 px-0">
    <div class="col-md-12 bg-success text-white pt-1 pb-1">
       メンバー
    </div>


     <div id="userDetails" class="row mx-2">

         <div class="col-md-12">
             <div class="text-secondary">Level:</div>
         </div>
         <div class="col-md-12">
             <div class="text-dark">
                 {{ $latestReportCard->lesson_level }}
             </div>
         </div>
        

         <div class="col-md-12">
             <div class="text-secondary" title="level"> Course</div>
         </div>
         <div class="col-md-12">
             <div class="text-dark">
                {{ $latestReportCard->lesson_course }}
             </div>
         </div>

         <div class="col-md-12">
             <div class="text-secondary">Lesson Material:</div>
         </div>
         <div class="col-md-12">
             <div class="text-dark">
                {{ $latestReportCard->lesson_material }}

             </div>
         </div>
         <div class="col-md-12">
             <div class="text-secondary">Lesson Subject:</div>
         </div>
         <div class="col-md-12">
             <div class="text-dark">
                {{ $latestReportCard->lesson_subject }}                
            </div>
         </div>
     </div>
 </div>
