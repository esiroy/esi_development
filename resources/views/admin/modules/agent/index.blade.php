@extends('layouts.admin')

@section('content')
<div class="container bg-light px-0">

        <div class="bg-lightblue2">
          <div class="container px-0">
            <nav class="submenu nav nav-pills flex-column flex-sm-row">
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-left border-primary" href="{{ url('admin/member') }}">Member</a>
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary" href="{{ url('admin/tutor') }}">Tutor</a>
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary"href="{{ url('admin/manager') }}">Manager</a>
                <a class="flex-sm text-sm-center nav-link font-weight-bold rounded-0 border-right border-primary active" href="{{ url('admin/agent') }}">Agent</a>
            </nav>
          </div>
        </div>

    <div class="esi-box">

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light ">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Agent</li>
            </ol>
        </nav>

        <div class="container">

            <!--[start card] -->
            <div class="card">
                <div class="card-header">
                    Agent List
                </div>
                <div class="card-body">
                    <div class="row">    
                        <form class="form-inline" style="width:100%">  
                            <div class="col-md-3">                   
                                <div class="form-group">
                                    <label for="nickname" class="small col-4">Username:</label>
                                    <input id="nickname" name="nickname" type="text" class="form-control form-control-sm col-8" value="">
                                </div>                        
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">   
                                    <label for="nickname" class="small col-4">Name:</label>                      
                                    <input id="name" name="name" type="text" class="form-control form-control-sm col-8" value="">
                                </div>
                            </div>                        
                            <div class="col-md-6">
                                <div class="form-group">   
                                    <label for="name" class="small col-2">Email:</label>
                                    <input id="name" name="name" type="text" class="form-control form-control-sm col-4" value="">                
                                    <button type="button" class="btn btn-primary btn-sm col-1 ml-1">Go</button>

                                </div>                                
                            </div>
                        </form>
                    </div>

                    <!--
                    <div class="row">
                        <div class="col-12 pt-3">
                            <button type="button" class="btn btn-primary btn-sm">Generate Agent List</button>
                        </div>
                    </div>
                    -->

                    <div class="row">
                        <div class="col-12 pt-3">
                            <!--<agent-list-component/>-->

                            <div class="table-responsive">
                                <table class="table table-bordered">
                                <thead>
                                    <tr>

                                        <th class="small text-center">Agent Name</th>
                                        <th class="small text-center">ID</th>
                                        <!--<th class="small text-center">Area</th>-->
                                        <th class="small text-center">Member<br/>List</th>
                                        <th class="small text-center">First Date of<br/>Purchase</th>
                                        <th class="small text-center">Point Purchase<br/>History</th>                                        
                                        <th class="small text-center">Point<br/>Balance</th>
                                        <th class="small text-center">Expire<br/>Data</th>
                                        <th class="small text-center">Purchase<br/>Amount</th>
                                        <th class="small text-center">Action</th>                                    
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($agents))
                                        @foreach ($agents as $agent)
                                        <tr>
                                            <td class="small">{{$agent->agentInfo->name_en}}</td>
                                            <td class="small">{{$agent->agentInfo->user_id}}</td>
                                            <!--<td class="small text-center">{{$agent->agentInfo->address}}</td>-->
                                            <td class="small text-center"><img src="/images/iMemberList.jpg"></td>
                                            <td class="small text-center">{{$agent->agentInfo->contract_date}}</td>
                                            <td class="small text-center"><img src="/images/iHistory.jpg"></td>

                                            <td class="small text-center">{{$agent->agentInfo->initial_date_of_purchase}}</td>
                                            <td class="small text-center">{{$agent->agentInfo->point_balance}}</td>
                                            <td class="small text-center">{{$agent->agentInfo->expire_date}}</td>
                                            <td class="small text-center">{{$agent->agentInfo->purchased_amount}}</td>

                                            <td class="small text-center">Account | Edit  | Delete </td>
                                        </tr>


                    
                                        @endforeach
                                    @endif

                                </tbody>
                                </table>
                            </div>

                        </div>                
                    </div>
                </div>
            </div><!--[end] card-->

            <div class="card mt-4">
                <div class="card-header">Agent Form</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-2 small">
                            <span class="text-danger">*</span>	業種
                        </div>
                        <div class="col-3">
                            <select name="industryType" class="form-control form-control-sm">
                                <option value="">-- Select Type --</option>
                                <option value="PRIVATE_SCHOOL">Private School</option>
                                <option value="PUBLIC_SCHOOL">Public School</option>
                                <option value="COMPANY">Company</option>
                                <option value="INDIVIDUAL">Individual</option>
                            </select>
                        </div>
                    </div>
                    <div class="row pt-2">
                        <div class="col-2 small">
                            <span class="text-danger">*</span>	Email
                        </div>
                        <div class="col-3">
                            <input type="text" name="email" class="form-control form-control-sm" placeholder="E-mail Address">
                        </div>
                    </div>
                    <div class="row pt-2">
                        <div class="col-2 small">
                            <span class="text-danger">*</span>	Password
                        </div>
                        <div class="col-3">
                            <input type="text" name="password" class="form-control form-control-sm" placeholder="Password">
                        </div>
                    </div>
                    <div class="row pt-2">
                        <div class="col-2 small">
                            <span class="text-danger">*</span>	Name (English)
                        </div>
                        <div class="col-3">
                            <input type="text" name="name_en" class="form-control form-control-sm" placeholder="Name (English)">
                        </div>
                    </div>
                    <div class="row pt-2">
                        <div class="col-2 small">
                            <span class="text-danger">*</span>	Name (Japanese)
                        </div>
                        <div class="col-3">
                            <input type="text" name="name_jp" class="form-control form-control-sm" placeholder="Name (Japanese)">
                        </div>
                    </div>                    
                    <div class="row pt-2">
                        <div class="col-2 small">
                           ID
                        </div>
                        <div class="col-3">
                            <input type="text" name="id" class="form-control form-control-sm" placeholder="Agent ID">
                        </div>
                    </div>
                    <div class="row pt-2">
                        <div class="col-2 small">
                           <span class="text-danger">*</span>	担当者
                        </div>
                        <div class="col-3">
                            <input type="text" name="representative" class="form-control form-control-sm" placeholder="担当者 Agent Representative">
                        </div>
                    </div>
                    <div class="row pt-2">
                        <div class="col-2 small">
                           ひらがな
                        </div>
                        <div class="col-3">
                            <input type="text" name="hiragana" class="form-control form-control-sm" placeholder="ひらがな Agent Hiragana">
                        </div>
                    </div>
                    <div class="row pt-2">
                        <div class="col-2 small">
                           Address
                        </div>
                        <div class="col-3">
                            <textarea name="address" class="form-control"></textarea>
                        </div>
                    </div>        
                    <div class="row pt-2">
                        <div class="col-2 small">
                           ふりがな
                        </div>
                        <div class="col-3">
                            <input type="text" name="inclination" class="form-control form-control-sm" placeholder="ふりがな Agent Inclination">
                        </div>
                    </div>
                    <div class="row pt-2">
                        <div class="col-2 small">
                           ポイント購入日
                        </div>
                        <div class="col-3">
                            <input type="text" name="contract_date" class="form-control form-control-sm" placeholder="ポイント購入日 Contract Date">
                        </div>
                    </div>                     
                    <div class="row pt-2">
                        <div class="col-2 small">
                           備考 
                        </div>
                        <div class="col-3">
                             <textarea name="remark" class="form-control" placeholder="備考 Agent Remark"></textarea>
                        </div>
                    </div> 
                    <div class="row pt-3">
                        <div class="col-2"></div>
                        <div class="col-3 text-left">
                            <button type="button" class="btn btn-primary btn-sm">Save</button>
                            <button type="button" class="btn btn-primary btn-sm">Cancel</button>
                        </div>
                    </div>
                           
                                       
                </div>
            </div>

        </div>
    </div>

</div>
@endsection