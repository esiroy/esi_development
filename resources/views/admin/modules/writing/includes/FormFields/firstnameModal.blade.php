    <!-- Modal -->
    <div id="modal_firstname" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">First Name : Field</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">

                    <form id="form_firstname">
                        <div class="fields">
                            <div class="row">
                                <div class="col">
                                    <label class="form-label">Field Label</label>
                                    <input type="text" id="label" name="label" class="form-control pt-0">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <label class="form-label">Description</label>
                                    <textarea id="description" name="description" class="form-control"></textarea>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col">
                                    <label class="form-label">Rules</label>
                                    <br/>
                                    <input id="required" name="required" type="checkbox" > <label class="form-label">Required</label>                                
                                </div>
                            </div>
                        </div>

                    </form>

                </div>


                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                    <button id="btnFirstNameSave" type="button" class="btn btn-primary" data-dismiss="modal">Save</button>
                </div>


            </div>

        </div>
    </div>