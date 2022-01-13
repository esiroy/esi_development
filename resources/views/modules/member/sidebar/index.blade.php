<div class="col-md-3">
    <div>
        @include('modules.member.sidebar.profile')
    </div>

    <div class="mt-3 mb-4">
        @include('modules.member.sidebar.customerchatsupport')
    </div>

    <div class="mt-3 mb-4">
        @include('modules.member.sidebar.reports')
    </div>


    <div class="mt-3 mb-4">
        @include('modules.member.sidebar.membertestscores')
    </div>

    @php
        /* php based memberscores
            <div class="mt-3 mb-4">
                    @include('modules.member.sidebar.memberpurpose')
            </div>
        */
    @endphp
    
</div>


@section('scripts')
@parent
    <script>
        window.addEventListener('load', function() 
        {  

            $('.examScoreHolder').hide();

            
           /*
            function getIELTSOverallScore() 
            {
                if ($('#examination-score-IELTS').find('#speakingBandScore').val() !== '' &&  
                    $('#examination-score-IELTS').find('#writingBandScore').val() !== '' && 
                    $('#examination-score-IELTS').find('#readingBandScore').val() !== '' &&  
                    $('#examination-score-IELTS').find('#listeningBandScore').val() !== '') 
                    {
                        let sum =  parseFloat($('#examination-score-IELTS').find('#speakingBandScore').val()) + 
                                parseFloat($('#examination-score-IELTS').find('#writingBandScore').val()) + 
                                parseFloat($('#examination-score-IELTS').find('#readingBandScore').val()) + 
                                parseFloat($('#examination-score-IELTS').find('#listeningBandScore').val());
                        let overall = sum / 4;
                        return overall;
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
                //console.log (filled_selection_length + " ? length ? " + selection.length);

                if (filled_selection_length == (selection.length - 1) ||   filled_selection_length == selection.length  ) 
                {

                    console.log("total :  " + total );
                    return parseInt(total);
                } else {
                    console.log("not all filled!")
                }
            }


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
                            $('#TOEFLtotal').val(getTotalScore('TOEFL')); 
                            $('#total').val(getTotalScore('TOEFL'))
                        break;

                        case "TOEFL_Junior":
                            //$('#TOEFL_Junior_totalScore').val(getTOEFLJuniorTotal());                            
                            $('#TOEFL_Junior_totalScore').val(getTotalScore('TOEFL_Junior')); 
                            $('#total').val(getTotalScore('TOEFL_Junior'))
                        break;

                        case 'TOEFL_Primary_Step_1':
                            $('#TOEFL_Primary_Step_1_totalScore').val(getTotalScore('TOEFL_Primary_Step_1')); 
                            $('#total').val(getTotalScore('TOEFL_Primary_Step_1'))
                        break;

                        
                        case 'TOEFL_Primary_Step_2':
                             $('#TOEFL_Primary_Step_2_totalScore').val(getTotalScore('TOEFL_Primary_Step_2')); 
                             $('#total').val(getTotalScore('TOEFL_Primary_Step_2'))
                        break;


                        case 'TOEIC_Listening_and_Reading':                            
                            $('#total').val(getTotalScore('TOEIC_Listening_and_Reading'))
                            $('#TOEIC_Listening_and_Reading_totalScore').val(total); 
                        break;


                        case 'TOEIC_Speaking':
                            $('#TOEIC_Speaking_totalScore').val(getTotalScore('TOEIC_Speaking')); 
                            $('#total').val(getTotalScore('TOEIC_Speaking'))
                        break;

                        case 'EIKEN':
                            $('#EIKEN_totalScore').val(getTotalScore('EIKEN')); 
                            $('#total').val(getTotalScore('EIKEN'))
                        break;


                        case 'TEAP':
                            $('#TEAP_totalScore').val(getTotalScore('TEAP')); 
                            $('#total').val(getTotalScore('TEAP'))
                        break;



                        default:
                    }
                });

            });

                */

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

        
            function getMemberExamScorePage(page, memberID)
            {
                $.ajax({
                    type: 'POST',
                    url: '/api/getAllMemberExamScore?page='+ page +'&api_token={{ Auth::user()->api_token }}',
                    data: {
                        limit: 1,
                        memberID: memberID
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function() 
                    {             
                        $('#loadingModal').modal('show');
                    },            
                    complete: function()
                    {
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



            /*
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
                //console.log (filled_selection_length + " ? length ? " + selection.length);

                if (filled_selection_length == (selection.length - 1) ||   filled_selection_length == selection.length  ) 
                {

                    console.log("total :  " + total );
                    return parseInt(total);
                } else {
                    console.log("not all filled!")
                }
            }*/



        });
    </script>
@endsection