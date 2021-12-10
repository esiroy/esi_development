    <!-- Modal -->
    <div id="modal_html" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Create HTML Text</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">

                    <form id="form_html">
                        <div class="fields">
                            <div class="row">
                                <div class="col">
                                    <label class="form-label">Field Label</label>
                                    <input type="text" id="label" name="label" class="form-control pt-0">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <label class="form-label">Content</label>
                                    <textarea id="content" name="content" class="ckEditor form-control"></textarea>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">                    
                                    <div id="insertImage" class="mb-2">
                                        <input id="btnInsertImage" type="button" class="btn btn-sm btn-primary" value="Insert Media File">
                                    </div>
                                    <!--
                                    <div id="insertAudio" class="mb-2">
                                        <input id="btnInsertAudio" type="button" class="btn btn-light" value="Insert Audio">
                                    </div>
                                    -->
                                </div>
                            </div>
                        </div>

                    </form>

                </div>


                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                    <button id="btnHTMLSave" type="button" class="btn btn-primary" data-dismiss="modal">Save</button>
                </div>


            </div>

        </div>
    </div>