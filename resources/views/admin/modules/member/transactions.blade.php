        <div class="container bg-light">
            <div class="card esi-card">
                <div class="card-header esi-card-header">
                    Member Details
                </div>
                <div class="card-body esi-card-body">
                    <form name="add_credit_transaction_form" method="POST" action="{{ route('admin.member.update',  [$member->id]) }}">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="row py-1">
                                    <div class="col-md-3">Member : </div>
                                    <div class="col-md-8"><strong>{{ $member->user->last_name }}, {{ $member->user->first_name }}</strong></div>
                                </div>

                                <div class="row py-1">
                                    <div class="col-md-3">Agent : </div>
                                    <div class="col-md-9">
                                    

                                        {{ $member->agent->name_en ?? " ~ " }}
                                    </div>
                                </div>

                                <div class="row py-1">
                                    <div class="col-md-3">Transaction : </div>
                                    <div class="col-md-9">
                                        <select name="transaction_type" id="transaction_type" class="form-control form-control-sm " onchange="checkSelected()">
                                            <option value="">-- Select Transaction Type --</option>
                                            <option value="DISTRIBUTE">Distribute</option>
                                            <option value="AGENT_SUBTRACT">Subtract (Agent)</option>
                                            <option value="CREDITS_EXPIRATION">Credits Expiration</option>
                                            <option value="FREE_CREDITS">Free Credits</option>
                                            <option value="MANUAL_ADD">Manual Add</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row py-1">
                                    <div class="col-md-3">Credits : </div>
                                    <div class="col-md-9">
                                    <input type="text" size="4" name="credits" id="credits" class="form-control form-control-sm col-md-3" alt="credits">
                                    </div>
                                </div>


                                <div class="row py-2">
                                    <div class="col-md-3">Amount : </div>
                                    <div class="col-md-9">
                                    <input type="text" size="4" name="amount" id="amount" class="form-control form-control-sm col-md-3" alt="amount">
                                    </div>
                                </div>

                                <div class="row py-2">
                                    <div class="col-md-3">Expiry Date : </div>
                                    <div class="col-md-9">
                                    <input type="date" name="expiry_date" id="expiry_date" class="form-control form-control-sm col-md-5" alt="expiry_date">
                                    </div>
                                </div>

                                <div class="row py-2">
                                    <div class="col-md-3">Remarks : </div>
                                    <div class="col-md-9"> 
                                        <textarea name="remarks" class="form-control form-control-sm"></textarea>
                                    </div>
                                </div>

                            </div>

                            <!--[start right column]-->
                            <div class="col-md-6">
                                <div class="row py-1">
                                    <div class="col-md-4">Credits : </div>
                                    <div class="col-md-8"><strong>{{ $member->credits }}</strong></div>
                                </div>
                                <div class="row py-1">
                                    <div class="col-md-4">Credits Expiration : </div>
                                    <div class="col-md-8"><strong>{{ $member->credits_expiration }}</strong></div>
                                </div>
                                <div class="row py-1">
                                    <div class="col-md-4">Latest Purchase Date : </div>
                                    <div class="col-md-8"><strong>{{ $member->latest_purchase_date }}</strong></div>
                                </div>
                            </div> <!--[end right column]-->            
                        </div><!--[end] first row-->

                        <div class="row">
                            <div class="col-md-12">                            
                                <input type="submit" class="btn btn-primary btn-sm" value="Add Transaction">
                            </div>
                        </div>  
                    </form>
                </div><!--[end] card body-->

            </div>
        </div>