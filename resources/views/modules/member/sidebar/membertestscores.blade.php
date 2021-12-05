<div class="profile bg-lightred pt-0 px-0">
    <div class="col-md-12 bg-red text-white pt-2 pb-2 text-center">
        <strong>テストスコア履歴</strong> 
        <strong><!-- Test Score History --></strong>
        <span class="btnAddScoreHolder float-right">
            <a href="javascript:void(0)" class="text-white" data-toggle="modal" data-target="#showAddMemberExamScoreModal">
                <i class="fas fa-plus"></i>
            </a>
        </span>
    </div>

    <div class="col-md-12  pt-2 pb-2 ">
        <div>
            <div class="mb-2 small font-weight-bold">
                Exam Date : <span id="memberExamDateLabel" class="font-weight-normal"> - </span>
            </div>

            <div class="mb-2 small font-weight-bold">
                Exam Type : <span id="memberExamTypeLabel" class="font-weight-normal"> - </span>
            </div>  

            <div id="memberExamScoresLabel" class="mb-2 small"></div>


        </div>
        <div class="text-center" >
            <a id="viewAllExamScores" href="getAllScores"  data-toggle="modal" data-target="#showAllMemberExamScoreModal">All Scores</a>
        </div>
    </div>

    <div>
        <div>
        </div>
    </div>
</div>


@section('scripts')
    @parent
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.3/moment.min.js"></script>
    <script>
    function capitalizeFirstLetter(string) {
        let newString = string.charAt(0).toUpperCase() + string.slice(1);
        newString = newString.replace(/_/g, " ")

        //add space before big letters
        return newString.replace(/([A-Z])/g, ' $1').trim(); 
    }      

    function formatMemberScores(scores) 
    {
        let obj = JSON.parse(scores);

        //clear first

        $('#memberExamScoresLabel').text("");

        Object.keys(obj).forEach(function(name) 
        {   
            let score = "<div class='mb-2'> <span class='font-weight-bold'>"+ capitalizeFirstLetter(name) +"</span> : "+ obj[name] +"</div>";
            $('#memberExamScoresLabel').append(score);
        });    
    }

    function getMemberLatestExamScore() {
        $.ajax({
            type: 'POST',
            url: 'api/getMemberLatestScore?api_token=' + api_token,
            data: {
                limit: 1,
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                if (data.success == true) 
                {   
                    $('#memberExamDateLabel').text(data.examDate);
                    
                    $('#memberExamTypeLabel').text(data.examType.replace(/_/g, ' '));

                    formatMemberScores(data.examScores);
                } else {
                    console.log(data.message);
                    return false;
                }
            }
        }); 
    }

    function getMemberExamScorePage(page)
    {
        $.ajax({
            type: 'POST',
            url: 'api/getAllMemberExamScore?page='+ page +'&api_token=' + api_token,
            data: {
                limit: 5,
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function() 
            {
               $('#showAllMemberExamScoreModal').css('visibility', 'hidden');
               $('#loadingModal').modal('show');
            },            
            complete: function(){
                $('#loadingModal').modal('hide');
                $('#showAllMemberExamScoreModal').css('visibility', 'visible');                
            },                                        
            success:function(data)
            {
                $('#memberExamScores').html(data.scores);               
                return false;
            }
        });
    }


    /*** COMPUTE TOTALS */
    function getIELTSOverallScore() 
    {
        if ($('#speakingBandScore').val() !== '' &&  $('#writingBandScore').val() !== '' && 
            $('#readingBandScore').val() !== '' &&  $('#listeningBandScore').val() !== '') 
            {
                let sum =  parseFloat($('#speakingBandScore').val()) + parseFloat($('#writingBandScore').val()) + parseFloat($('#readingBandScore').val()) + parseFloat($('#listeningBandScore').val());        
                let overall = sum / 4;
                return overall;
            }
    }


    function getTOEFLTotal() 
    {
        if ($('#TOEFL_speakingScore').val() !== '' && $('#TOEFL_writingScore').val() !== '' &&
            $('#TOEFL_readingScore').val() !== '' && $('#TOEFL_listeningScore').val() !== '') 
            {
                let total = parseInt($('#TOEFL_speakingScore').val()) 
                        + parseInt($('#TOEFL_writingScore').val()) 
                        + parseInt($('#TOEFL_readingScore').val()) 
                        + parseInt($('#TOEFL_listeningScore').val());
                return total;           
            } 
    }

    function getTOEFLJuniorTotal() 
    {
       if ($('#TOEFL_Junior_listeningScore').val() !== '' && $('#TOEFL_Junior_languageFormAndMeaningScore').val() !== '' &&
            $('#TOEFL_Junior_readingScore').val() !== '' ) 
            {
                let total = parseInt($('#TOEFL_Junior_listeningScore').val()) 
                        + parseInt($('#TOEFL_Junior_languageFormAndMeaningScore').val()) 
                        + parseInt($('#TOEFL_Junior_readingScore').val());
                return total;           
            }
    }


    function getTOEFLTotal() 
    {
        if ($('#TOEFL_speakingScore').val() !== '' && $('#TOEFL_writingScore').val() !== '' &&
            $('#TOEFL_readingScore').val() !== '' && $('#TOEFL_listeningScore').val() !== '') 
            {
                let total = parseInt($('#TOEFL_speakingScore').val()) 
                        + parseInt($('#TOEFL_writingScore').val()) 
                        + parseInt($('#TOEFL_readingScore').val()) 
                        + parseInt($('#TOEFL_listeningScore').val());
                return total;           
            } 
    }


    function getTOEFLTotal() 
    {
        if ($('#TOEFL_speakingScore').val() !== '' && $('#TOEFL_writingScore').val() !== '' &&
            $('#TOEFL_readingScore').val() !== '' && $('#TOEFL_listeningScore').val() !== '') 
            {
                let total = parseInt($('#TOEFL_speakingScore').val()) 
                        + parseInt($('#TOEFL_writingScore').val()) 
                        + parseInt($('#TOEFL_readingScore').val()) 
                        + parseInt($('#TOEFL_listeningScore').val());
                return total;           
            } 
    }        



    function getTotalScore(ExamType) 
    {

        let selection = $('div#examination-score-'+ExamType).find('select');
        console.log(selection.length);

        let total = 0;
        let filled_selection_length = 0;

        selection.each(function() 
        {
            let elementID = $(this).attr('id');
            let numeric = parseInt($(this).val())
            if($.isNumeric(numeric)) 
            {
                filled_selection_length++

                if (elementID.includes("total")) {
                    //this will not be added to total score, since this is a total score element
                } else {
                    total = parseInt(total) + parseInt($(this).val());                    
                    console.log($(this).attr('id') + " " + parseInt($(this).val() ));
                }
            } else {
                console.log("empty");
            }
 
        });


        console.log (filled_selection_length + " ? length ? " + selection.length);

        if (filled_selection_length == (selection.length - 1) ||   filled_selection_length == selection.length  ) 
        {

            console.log("total :  " + total );
            return parseInt(total);
        } else {
            console.log("not all filled!")
        }
    }



    window.addEventListener('load', function() 
    {   
        getMemberLatestExamScore();

        $('#viewAllExamScores').on('click', function() 
        {        
            getMemberExamScorePage(1);
        });

        //Pagination of examp pages
        $(document).on('click', '#memberExamScores .pagination a', function(event){
            event.preventDefault(); 
            var page = $(this).attr('href').split('page=')[1];
            getMemberExamScorePage(page);
            return false;
        });

        //Exam Entries
        jQuery(".inputDate").on("change", function() {
            this.setAttribute("data-date", moment(this.value, "YYYY-MM-DD").format(this.getAttribute("data-date-format")))
        }).trigger("change")

        jQuery(".inputDateIcon").on("click", function(){
            jQuery(".inputDate").trigger("change");
        })

        $('#loadingModal').modal('hide');
        $('#loadingModal').hide();

        $('#showAddMemberExamScoreModal').on('hide.bs.modal', function () {
            $('.examScoreHolder').hide();
        });

        //Cancel Add Score
        $('#cancelAddScore').on('click', function() {    
            $('.examScoreHolder').hide();
            $('#submitFormExamScore').trigger('reset');
            $('#showAddMemberExamScoreModal').modal('hide');
        });

        //Submit Examp Score
        $('#submitFormExamScore').on('submit', function()
        {
            let examDate = $('#examDate').val();
            let examType = $('#examType').val();  

            let examScores = {};

            switch(examType) {
                case "IELTS":
                         examScores = {            
                            IELTS : {
                                overallBandScore: $('#overallBandScore').val(),
                                speakingBandScore: $('#speakingBandScore').val(),
                                writingBandScore: $('#writingBandScore').val(),
                                readingBandScore: $('#readingBandScore').val(),
                                listeningBandScore: $('#listeningBandScore').val(),
                            }
                        }
                    break;
                case "TOEFL":
                         examScores = {            
                            TOEFL: {
                                speakingScore: $('#TOEFL_speakingScore').val(),
                                writingScore: $('#TOEFL_writingScore').val(),
                                readingScore: $('#TOEFL_readingScore').val(),
                                listeningScore: $('#TOEFL_readingScore').val(),
                                total: $('#TOEFL_total').val(),
                            }
                        }
                     break;
                case "TOEFL_Junior":
                         examScores = {            
                            TOEFL_Junior: {                                
                                listening: $('#TOEFL_Junior_listeningScore').val(),
                                languageFormAndMeaning: $('#TOEFL_Junior_languageFormAndMeaningScore').val(),
                                reading: $('#TOEFL_Junior_readingScore').val(),
                                total: $('#TOEFL_Junior_totalScore').val(),                                
                            }
                        }
                    break;
                case 'TOEFL_Primary_Step_1': 
                         examScores = {            
                            TOEFL_Primary_Step_1: {                                                                
                                listening: $('#TOEFL_Primary_Step_1_listeningScore').val(),   
                                reading: $('#TOEFL_Primary_Step_1_readingScore').val(),                    
                                total: $('#TOEFL_Primary_Step_1_totalScore').val(),                                
                            }
                        }
                    break;                    
                case 'TOEFL_Primary_Step_2': 
                         examScores = {            
                            TOEFL_Primary_Step_2: {                                                        
                                listening: $('#TOEFL_Primary_Step_2_listeningScore').val(),
                                reading: $('#TOEFL_Primary_Step_2_readingScore').val(),
                                total: $('#TOEFL_Primary_Step_2_totalScore').val(),
                            }
                        }
                    break;

                case 'TOEIC_Listening_and_Reading':
                        examScores = {   
                            TOEIC_Listening_and_Reading: {                                
                                listening: $('#TOEIC_Listening_and_Reading_listeningScore').val(),
                                reading: $('#TOEIC_Listening_and_Reading_readingScore').val(),                                
                                total: $('#TOEIC_Listening_and_Reading_totalScore').val(),
                            }
                        }
                     break;

                case 'TOEIC_Speaking':
                        examScores = {                           
                            TOEIC_Speaking: {
                                speaking: $('#TOEIC_Speaking_listeningScore').val(),
                            }
                        }
                     break;

                case 'EIKEN':
                        examScores = {           
                            EIKEN: {
                                grade_5: $('#EIKEN_grade_5').val(),
                                grade_4: $('#EIKEN_grade_4').val(),
                                grade_3_1st_stage: $('#EIKEN_grade_3_1st_stage').val(),
                                grade_pre_2_1st_stage: $('#EIKEN_grade_pre_2_1st_stage').val(),
                                grade_2_1st_stage: $('#EIKEN_grade_2_1st_stage').val(),
                                grade_pre_1_1st_stage: $('#EIKEN_grade_pre_1_1st_stage').val(),
                                grade_1_1st_stage: $('#EIKEN_grade_1_1st_stage').val(),

                                grade_3_2nd_stage: $('#EIKEN_grade_3_2nd_stage').val(),
                                grade_pre_2_2nd_stage: $('#EIKEN_grade_pre_2_2nd_stage').val(),
                                grade_2_2nd_stage: $('#EIKEN_grade_2_2nd_stage').val(),
                                grade_pre_1_2nd_stage: $('#EIKEN_grade_pre_1_2nd_stage').val(),
                                grade_1_2nd_stage: $('#EIKEN_grade_1_2nd_stage').val(),                    
                            }
                        }
                    break;

                case 'TEAP':
                        examScores = {    
                            TEAP: {
                                total: "",
                                speakingScore: $('#TEAP_speakingScore').val(),
                                writingScore: $('#TEAP_writingScore').val(),
                                readingScore: $('#TEAP_readingScore').val(),
                                listeningScore: $('#TEAP_readingScore').val(),
                                total: $('#TEAP_totalScore').val(),                                           
                            }
                        }
                    break;
                    
                case 'Other_Test':
                    examScores = { 
                        Other_Test: {
                            otherScore: $('#OTHERS_score').val(),
                        }
                    }
                    break;

                default:
                    alert ('Please select exam type')
                    return false;
            }


            $.ajax({
                type: 'POST',
                url: 'api/addMemberExamScore?api_token=' + api_token,
                data: {
                    examDate: examDate,
                    examType: examType,
                    examScore: examScores                
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() 
                {
                    $('#showAddMemberExamScoreModal').css('visibility', 'hidden');
                    $('#loadingModal').modal('show');
                },                   
                success: function(data) {

                    $('#showAddMemberExamScoreModal').css('visibility', 'visible');

                    if (data.success == true) 
                    {  
                        $('#memberExamDateLabel').text(data.examDate);
                        $('#memberExamTypeLabel').text(data.examType.replace(/_/g, ' '));

                        formatMemberScores(data.examScores);
                        //hide modal and reset form
                        $('#showAddMemberExamScoreModal').modal('hide');
                        $('#showAddMemberExamScoreModal #submitFormExamScore').trigger('reset');
                        //show message modal
                        $('#msgboxSuccessModal').modal('show');
                        $('#msgboxSuccessModal #msgboxMessage').html("<i class='fas fa-check' style='color:#28a745'></i> Exam Score Added");
                     
                    } else {
                        alert(data.message);
                        return false;
                    }
                }
            });            

            return false;
            
        });


        $(document).ready(function() 
        {
            $(document).on('change', '#examType', function(event) 
            {               
                let examTypeValue = $(this).val();                    
                let examType = examTypeValue.replace(/\s+/g, '-');
                
                $('.examScoreHolder').hide();
                $('#examination-score-'+ examType).show();

                let listChangeSelect = $('#examination-score-'+ examType).find('.form-control');

                $(listChangeSelect).on('change', function(event)
                {       
                    switch(examType) 
                    {
                        case "IELTS":
                            $('#overallBandScore').val(getIELTSOverallScore());
                        break;

                        case "TOEFL":                           
                               //$('#TOEFL_total').val(getTOEFLTotal());
                               $('#TOEFL_total').val(getTotalScore('TOEFL')); 
                        break;

                        case "TOEFL_Junior":
                            //$('#TOEFL_Junior_totalScore').val(getTOEFLJuniorTotal());                            
                            $('#TOEFL_Junior_totalScore').val(getTotalScore('TOEFL_Junior')); 
                        break;

                        case 'TOEFL_Primary_Step_1':
                            $('#TOEFL_Primary_Step_1_totalScore').val(getTotalScore('TOEFL_Primary_Step_1')); 
                        break;

                        
                        case 'TOEFL_Primary_Step_2':
                             $('#TOEFL_Primary_Step_2_totalScore').val(getTotalScore('TOEFL_Primary_Step_2')); 
                        break;


                        case 'TOEIC_Listening_and_Reading':
                            let total = getTotalScore('TOEIC_Listening_and_Reading')
                            $('#TOEIC_Listening_and_Reading_totalScore').val(total); 
                        break;


                        case 'TOEIC_Speaking':
                            $('#TOEIC_Speaking_totalScore').val(getTotalScore('TOEIC_Speaking')); 
                        break;

                        case 'EIKEN':
                            //$('#EIKEN_totalScore').val(getTotalScore('EIKEN')); 
                        break;


                        case 'TEAP':
                            $('#TEAP_totalScore').val(getTotalScore('TEAP')); 
                        break;



                        default:
                    }
                });

            });


        });        

    });
    </script>
@endsection



@section('styles')
    @parent
    <style>

        #loadingModal  {
            z-index: 99999;

        }
    </style>
@endsection
