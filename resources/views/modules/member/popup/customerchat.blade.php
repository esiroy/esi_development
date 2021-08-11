<div class="modal fade" id="customerChatSupportModal" tabindex="-1" role="dialog" aria-labelledby="customerChatSupport" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl" role="document" style="margin-top:100px">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tutorMemoLabel">Customer support</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
               <div id="chat"></div>
            </div>
            
        </div>
    </div>
</div>


@section('scripts')                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         ti
@parent
<script>
function prepareFrame() {
    var ifrm = document.createElement("iframe");
    ifrm.setAttribute("src", "{{ url('customerchatsupport') }}");
    ifrm.style.width = "100%";
    ifrm.style.height = "285px";
    ifrm.style.border = "none";
    ifrm.style.scrolling = "no";
    //appendChild(ifrm);
    $( "#chat" ).html(ifrm);
}

    
window.addEventListener('load', function() 
{       
   $('#customerChatSupportModal').on('show.bs.modal', function (e) {
        prepareFrame(); 
    });

    $('#customerChatSupportModal').on('hide.bs.modal', function (e) {
        $( "#chat" ).html(""); 
    })

});
</script>
@endsection

@section('styles')
@parent
<style>
iframe  
{
    overflow:hidden
}
</style>
@endsection