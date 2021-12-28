<div id="form-navigation" class="card-body esi-card-body">              
    <div class="form-inline">
        <a class='text-success' href="{{ url('admin/writing/?id='.$form_id) }}">
            <button class="btn btn-sm btn-outline-success mr-2" type="button">
                Edit
            </button>
        </a>
        <a class='text-secondary' href="{{ url('admin/writing/entries/'.$form_id) }}">
            <button class="btn btn-sm btn-outline-secondary mr-2" type="button">Entries</button>
        </a>
        <a class='text-secondary' href="{{ url('admin/writing/preview/'.$form_id) }}">
            <button class="btn btn-sm btn-outline-secondary mr-2" type="button">                                
                Preview
            </button>
        </a>                            
    </div>                                                
</div>