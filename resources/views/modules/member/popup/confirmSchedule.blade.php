@php 
    $multiAccounts = new App\Models\MemberMultiAccountAlias;
    $memberAccounts = $multiAccounts->getMemberSelectedAccounts(Auth::user()->id);

    //Determine Session
    $sessionMultiAccountID = Session::get('accountID');

    if (isset($sessionMultiAccountID)) 
    {
        $sessionAccount = $multiAccounts->getMemberAccountInfo(Auth::user()->id, $sessionMultiAccountID);
        $activeAccount = $sessionAccount;
        
        
    } else {
        $activeAccount = $multiAccounts->getMemberDefaultAccount(Auth::user()->id);
    }
@endphp

<div class="modal" id="confirmScheduleModal" tabindex="-1" role="dialog" aria-labelledby="confirmScheduleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Confirm Schedule</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h6 class="modal-title" id="confirmScheduleModalLabel"><!-- Model Message --></h6>

        @if(count($memberAccounts) >= 1)
        <form>
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Account</label>
            <select name="accounts" id="accounts" class="form-control form-control-sm">
                @foreach($memberAccounts as $account)
                <option class="small" value="{{ $account->member_multi_account_id }}" @if ($account->member_multi_account_id == $activeAccount->member_multi_account_id) {{ 'selected' }} @endif>
                    {{$account->name}} @if ($account->is_default) <span>(default)</span> @endif
                </option>
                @endforeach
            </select>
          </div>          
        </form>
        @endif

      </div>
      <div class="modal-footer">
        <button id="btn-cancel-schedule" type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button id="btn-confirm-schedule" type="button" class="btn btn-primary">Confirm Schedule</button>
      </div>
    </div>
  </div>
</div>
