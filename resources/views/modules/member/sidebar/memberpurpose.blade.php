<div class="profile bg-lightBrown pt-0 px-0">
    <div class="col-md-12 bg-brown text-white pt-2 pb-2 text-center">
        <strong>受講目的</strong> 
        <strong><!-- Purpose --></strong>
        <span class="btnUpdatePurpose float-right">
            <a href="javascript:void(0)" class="text-white" data-toggle="modal" data-target="#showUpdateMemberPurposeModal">
                <i class="fas fa-plus"></i>
            </a>
        </span>
    </div>

    <div class="col-md-12  pt-2 pb-2 ">
        <div id="memberPurposeList">
            {!! $purposeListView ?? '' !!}
        </div>
       
    </div>

    <div>
        <div>
        </div>
    </div>
</div>


@section('scripts')
    @parent    
    <script>    
    function getMemberPurpose() 
    {       
        $.ajax({
            type: 'POST',
            url: 'api/getMemberPurpose?api_token=' + api_token,            
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function() 
            {
               $('#showUpdateMemberPurposeModal').css('visibility', 'hidden');
               //$('#loadingModal').modal('show');
            },            
            complete: function(){
                //$('#loadingModal').modal('hide');
                $('#showUpdateMemberPurposeModal').css('visibility', 'visible');                
            },              
            success: function(data) 
            {
                //$('#loadingModal').modal('hide');
                //$('#loadingModal').hide();

                if (data.success == true) 
                {   
                    $('#submitFormMemberPurpose .container').html(data.purposeForm);

                    //check member purpose on load
                    $('.main_option').each(function(i, obj) {
                        if ($(this).is(':checked')) {
                            $(this).next().show();
                        } else {
                            $(this).next().hide();
                        }  
                    });                           

                    //check member purpose on load
                    $('.sub_options input').each(function(i, obj) {
                        //console.log( $(this).val() + " " + $(this)[0].checked);

                        if ($(this).is(':checked'))             
                        {

                            $(this).next().next().show();
                        } else {
                            $(this).next().next().hide();
                        }
                    });                  


                } else {

                    console.log(data.message);
                    return false;
                }
            }
        }); 
    }  


    function getMemberPurposeList() {
        $.ajax({
            type: 'POST',
            url: 'api/getMemberPurposeList?api_token=' + api_token,            
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                if (data.success == true) {
                    $('#memberPurposeList').html(data.content);
                } else {
                   

                     $('#memberPurposeList').html(data.message);
                    return false;
                }
            }
        });     
    }


    function updatePurpose() {
        $.ajax({
            url: 'api/updateMemberPurpose?api_token=' + api_token,     
            type: 'post',
            dataType: 'json',
            data: $('form#submitFormMemberPurpose').serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },            
            success: function(data) {
                $('#showUpdateMemberPurposeModal').modal('hide');
                //$('#showUpdateMemberPurposeModal #submitFormMemberPurpose').trigger('reset');
                //show message modal
                $('#msgboxSuccessModal').modal('show');
                $('#msgboxSuccessModal #msgboxMessage').html("<i class='fas fa-check' style='color:#28a745'></i> " + data.message );

                //refresh list on sidebar
                getMemberPurposeList();
            }
        });

    }

    window.addEventListener('load', function() 
    {  
        getMemberPurposeList();

        $('#updatePurpose').on('click', function(){
           updatePurpose();
        });

        $('.btnUpdatePurpose a').on('click', function(){
           getMemberPurpose();
        });

        //Cancel Purpose Update
        $('#cancelUpdatePurpose').on('click', function() {    
            $('#showUpdateMemberPurposeModal #submitFormMemberPurpose').trigger('reset');                
            $('#showUpdateMemberPurposeModal').modal('hide');
        });

        $('#loadingModal').modal('hide');
        $('#loadingModal').hide();
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
