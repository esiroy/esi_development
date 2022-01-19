    <!-- Modal -->
    <div id="modal_dropdownTeacherSelect" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Select Teacher</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">

                    <form id="form_dropdownTeacherSelect">
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
                                    <textarea id="modal_dropdown_teacher_description" name="modal_dropdown_teacher_description" class="ckEditor form-control" style="height:30px"></textarea>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <label class="form-label">Choices</label>
                                    <div id="select_choices" class="container border p-2">

                                        @php
                                            $tutor = new App\Models\Tutor();
                                            $tutors = $tutor->getTutors();                                            
                                        @endphp

                                        <select id="teacher" class="form-control ">
                                            <option id="" >Select Teacher</option>
                                            @foreach($tutors as $tutor)
                                                <option id="{{ $tutor->id }}" >{{ $tutor->firstname }}</option>
                                            @endforeach
                                        <select>

                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col">
                                    <label class="form-label">Rules</label>
                                    <br/>
                                    <input id="required" name="required" type="checkbox" value="true"> <label class="form-label">Required</label>                                
                                </div>
                            </div>


                        </div>

                    </form>

                </div>


                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                    <button id="btnDropdownTeacherSelectSave" type="button" class="btn btn-primary" data-dismiss="modal">Save</button>
                </div>
            </div>
        </div>
    </div>