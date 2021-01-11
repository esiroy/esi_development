<!--@note: change password form-->


<div class="mt-3">

    <div class="bg-gray p-1">
        <div class="pl-2 font-weight-bold small">Change Password</div>
    </div>

    <div class="form">

        <div id="member-password-row" class="row pt-2">
            <div class="col-12">

                <form method="POST" action="{{ route('settings.updatePassword', $member->id) }}">
                    @csrf
                    @method('put')

                    <div class="row">
                        <div class="col-2 small pr-0"><label for="currentPassword" class="px-0 col-md-12 col-form-label">
                                <span class="text-danger">*</span> Current Password <div class="float-right">:</div></label>
                        </div>
                        <div class="col-6">
                            <input type="password" id="currentPassword" name="currentPassword" class="form-control form-control-sm @error('currentPassword') is-invalid @enderror" >

                            @error('currentPassword')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror

                        </div>
                    </div>


                    <div class="row">
                        <div class="col-2 small pr-0">
                            <label for="newPassword" class="px-0 col-md-12 col-form-label">
                                <span class="text-danger">*</span> New Password <div class="float-right">:</div>
                            </label>
                        </div>
                        <div class="col-6">
                            <input type="password" id="newPassword" name="newPassword" class="form-control form-control-sm @error('newPassword') is-invalid @enderror"  >

                            @error('newPassword')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror

                        </div>
                    </div>


                    <div class="row">
                        <div class="col-2 small pr-0">
                            <label for="confirmPassword" class="px-0 col-md-12 col-form-label">
                                <span class="text-danger">*</span> Retype Password <div class="float-right">:</div>
                            </label>
                        </div>
                        <div class="col-6">
                            <input type="password" id="confirmPassword" name="confirmPassword" class="form-control form-control-sm @error('confirmPassword') is-invalid @enderror" >

                            @error('password')
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
                            <button type="submit" class="btn btn-primary btn-sm">Change Password</button>

                        </div>
                    </div>

                </form>


            </div>
        </div>


    </div>

</div>
