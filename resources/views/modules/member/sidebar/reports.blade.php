<!--latest report cards-->

 <div class="profile bg-lightgreen pt-0 pb-4 px-0">
    <div class="col-md-12 bg-green text-white pt-2 pb-2 text-center">
       <strong> Level / Recent Lesson</strong>
    </div>


     <div id="userDetails-{{ $latestReportCard->id ?? "" }}" class="row mx-0 pt-2" >

        <!--
        @php 
        /*
         <div class="col-md-12">
             <div class="text-secondary">Level: 
                <a href="javascript:void(0);" class="text-danger" onclick="window.open('{{ url('stagelevel') }}','popup','width=650,height=500,toolbar=no,scrollbars=yes,resizable=yes,menubar=no,status=no,location=no,directories=no')">
                    <i>(１０段階レベル表)</i>
                </a>
             </div>
         </div>
         <div class="col-md-12">
             <div class="text-dark">
                 {{ $latestReportCard->lesson_level ?? "" }}
             </div>
         </div>
         */
         @endphp
         -->
        

         <div class="col-md-12">
             <div class="text-secondary" title="level">Course:</div>
         </div>
         <div class="col-md-12">
             <div class="text-dark">
                {{ $latestReportCard->lesson_course ?? "" }}
             </div>
         </div>

         <div class="col-md-12">
             <div class="text-secondary">Lesson Material:</div>
         </div>
         <div class="col-md-12">
             <div class="text-dark">
                {{ $latestReportCard->lesson_material ?? ""}}

             </div>
         </div>
         <div class="col-md-12">
             <div class="text-secondary">Lesson Subject:</div>
         </div>
         <div class="col-md-12">
             <div class="text-dark">
                {{ $latestReportCard->lesson_subject ?? "" }}                
            </div>
         </div>
     </div>
 </div>
