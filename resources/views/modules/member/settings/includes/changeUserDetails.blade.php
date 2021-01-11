<!--@note: change password form-->


<div class="mt-3">

    <div class="bg-gray p-1">
        <div class="pl-2 font-weight-bold small">Change Password</div>
    </div>

    <div class="form">

        <div id="member-password-row" class="row pt-2">
            <div class="col-12">

                <form method="POST" action="{{ route('settings.update', $member->id) }}">
                    @csrf
                    @method('put')

                    <div class="row">
                        <div class="col-2 small pr-0"><label for="japanese_firstname" class="px-0 col-md-12 col-form-label">
                                <span class="text-danger">*</span>Japanese First Name <div class="float-right">:</div></label>
                        </div>
                        <div class="col-6">
                            <input type="text" id="japanese_firstname" name="japanese_firstname" class="form-control form-control-sm @error('japanese_firstname') is-invalid @enderror"
                                value="{{ old('japanese_firstname', isset($member->japanese_firstname ) ? $member->japanese_firstname : '') }}">

                            @error('japanese_firstname')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror

                        </div>
                    </div>


                    <div class="row">
                        <div class="col-2 small pr-0">
                            <label for="japanese_lastname" class="px-0 col-md-12 col-form-label">
                                <span class="text-danger">*</span> Japanese Last Name<div class="float-right">:</div>
                            </label>
                        </div>
                        <div class="col-6">
                            <input type="text" id="japanese_lastname" name="japanese_lastname" class="form-control form-control-sm @error('japanese_lastname') is-invalid @enderror"
                                value="{{ old('japanese_lastname', isset($member->japanese_lastname ) ? $member->japanese_lastname : '') }}">

                            @error('japanese_lastname')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror

                        </div>
                    </div>


                    <div class="row">
                        <div class="col-2 small pr-0">
                            <label for="email" class="px-0 col-md-12 col-form-label">
                                <span class="text-danger">*</span> Email <div class="float-right">:</div>
                            </label>
                        </div>
                        <div class="col-6">
                            <input type="text" id="email" name="email" class="form-control form-control-sm @error('email') is-invalid @enderror" 
                                value="{{ old('email', isset($member->email ) ? $member->email : '') }}">

                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror

                        </div>
                    </div>


                    <div class="row">
                        <div class="col-2 small pr-0">
                            <label for="items_per_page" class="px-0 col-md-12 col-form-label">
                                <span class="text-danger">*</span> Items Per Page <div class="float-right">:</div>
                            </label>
                        </div>
                        <div class="col-6">
                            <select name="itemsPerPage" class="form-control form-control-sm col-md-2 @error('itemsPerPage') is-invalid @enderror">
	  							<option value="5" @if ($member->items_per_page == 5) {{ "selected" }} @endif >5</option>
	  							<option value="10" @if ($member->items_per_page == 10) {{ "selected" }} @endif>10</option>					  							
	  							<option value="20" @if ($member->items_per_page == 20) {{ "selected" }} @endif>20</option>					  												  							
	  							<option value="50" @if ($member->items_per_page == 50) {{ "selected" }} @endif>50</option>					  												  												  							
	  							<option value="100" @if ($member->items_per_page == 100) {{ "selected" }} @endif>100</option>					  												  												  												  							
	  						</select>
                              
                            @error('itemsPerPage')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror

                        </div>
                    </div>


                    <div class="row">
                        <div class="col-2 small pr-0">
                        </div>
                        <div class="col-6">
                            <button type="submit" class="btn btn-primary btn-sm">Update User Details</button>
                        </div>
                    </div>



                </form>


            </div>
        </div>


    </div>

</div>
