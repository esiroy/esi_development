<div class="col-md-3">
    <div>
        @include('modules.member.sidebar.profile')
    </div>

    <div class="mt-3 mb-4">
        @include('modules.member.sidebar.customerchatsupport')
    </div>


    @php /*
    <div class="mt-3 mb-4">
         @include('modules.member.sidebar.memberlevel')
    </div>
    */ @endphp
   
    
    <div class="mt-3 mb-4">
        @include('modules.member.sidebar.reports')
    </div>


    
    <div class="mt-3 mb-4">
        @include('modules.member.sidebar.membertestscores')
    </div>

    
    <div class="mt-3 mb-4">
        @include('modules.member.sidebar.memberpurpose')
    </div>
   

</div>


@section('scripts')
@parent
    <script>
        window.addEventListener('load', function() 
        {  

            $('.examScoreHolder').hide();

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

        });

        
    </script>
@endsection
