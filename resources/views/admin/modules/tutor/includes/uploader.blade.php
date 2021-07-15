<div id="actions" class="row mt-2">

    <div class="col-lg-12">
        <!-- The fileinput-button span is used to style the file input field as button -->
        <span class="btn btn-success btn-sm fileinput-button dz-clickable">
            <i class="glyphicon glyphicon-plus"></i>
            <span>Add files...</span>
        </span>
        <button type="submit" class="btn btn-primary btn-sm start" style="display:none">
            <i class="glyphicon glyphicon-upload"></i>
            <span>Start upload</span>
        </button>
        
        <button type="reset" class="btn btn-warning btn-sm cancel" style="display:inline-block">
            <i class="glyphicon glyphicon-ban-circle"></i>
            <span>Cancel upload</span>
        </button>
       
    </div>

    <div class="col-lg-5">
    <!-- The global file processing state -->
    <span class="fileupload-process">
        <div id="total-progress" class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0" style="opacity: 0;">
        <div class="progress-bar progress-bar-success" style="width: 100%;" data-dz-uploadprogress=""></div>
        </div>
    </span>
    </div>

</div>
