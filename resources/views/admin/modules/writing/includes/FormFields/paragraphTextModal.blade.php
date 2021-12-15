    <!-- Modal -->
    <div id="modal_paragraphText" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Create Paragraph Text</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">

                    <form id="form_paragraphText">
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
                                    <textarea id="modal_paragraphText_description" name="modal_paragraphText_description" class="ckEditor form-control"></textarea>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <label class="form-label">Enable Word Limit</label>
                                    <input type="checkbox" id="enableWordLimit" name="enableWordLimit" >
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <label class="form-label">Word Limit</label>
                                    <input type="text" id="wordLimit" name="wordLimit" class="form-control pt-0 col-md-3">
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
                    <button id="btnParagraphTextSave" type="button" class="btn btn-primary" data-dismiss="modal">Save</button>
                </div>


            </div>

        </div>
    </div>