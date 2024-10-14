<!--@note: change password form-->


<div class="mt-3">

    <div class="bg-gray p-1">
        <div class="pl-2 font-weight-bold small">General Settings</div>
    </div>

    <div class="form">

        <div id="member-password-row" class="row pt-2">
            <div class="col-12">

                <form method="POST" action="{{ route('admin.settings.updateGeneralSettings', $user->id) }}">
                    @csrf
                    @method('put')

                    <div class="row">
                        <div class="col-2 small pr-0"><label for="currentPassword" class="px-0 col-md-12 col-form-label">
                            <span class="text-danger">*</span> Activate NetEnglish? <div class="float-right">:</div></label>
                        </div>
                        <div class="col-6">

                            <input type="checkbox" id="is_netenglish" name="is_netenglish" value="1" @if($is_netenglish == true) {{ "checked" }} @endif>
                            

                            @error('currentPassword')
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

                        
                            <button type="submit" class="btn btn-primary btn-sm">Update General Settings</button>

                        </div>
                    </div>

                </form>


            </div>
        </div>


    </div>

</div>
