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
            <div id="memberExamDate">-</div>
            <div id="memberExamType">-</div>
            <div id="memberExamScore">-</div>
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
                    $('#memberExamDate').text(data.examDate)
                    $('#memberExamType').text(data.examType)
                    $('#memberExamScore').text(data.examScore)    
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


        //Cancel Add Score
        $('#cancelAddScore').on('click', function() {        
            $('#submitFormExamScore').trigger('reset');
            $('#showAddMemberExamScoreModal').modal('hide');
        });

        //Submit Examp Score
        $('#submitFormExamScore').on('submit', function()
        {
            let examDate = $('#examDate').val();
            let examType = $('#examType').val();
            let examScore = $('#examScore').val();

            $.ajax({
                type: 'POST',
                url: 'api/addMemberExamScore?api_token=' + api_token,
                data: {
                    examDate: examDate,
                    examType: examType,
                    examScore: examScore
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
                        $('#memberExamDate').text(examDate);
                        $('#memberExamType').text(examType);
                        $('#memberExamScore').text(examScore);

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
