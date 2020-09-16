@extends('layouts.admin')

@section('content')
<div class="container bg-light px-0">

    <div class="bg-lightblue2">
        <div class="container px-0">
            <nav class="submenu nav nav-pills flex-column flex-sm-row">
                <a class="flex-sm text-sm-center nav-link font-weight-bold rounded-0 border-right border-left border-primary active" href="{{ url('admin/lesson') }}">Questionnaires</a>
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary" href="{{ url('admin/announcement') }}">Message</a>
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary" href="{{ url('admin/accounts') }}">Accounts</a>
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary" href="{{ url('admin/course') }}">Material</a>
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary" href="{{ url('admin/course') }}">Company</a>
            </nav>
        </div>
    </div>

    <div class="esi-box">

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light ">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Members</li>
            </ol>
        </nav>


        <div class="container">
            <!--[start card] -->
            <div class="card">
                <div class="card-header">
                    Questionnaires
                </div>
                <div class="card-body">

                    <!--Search-->
                    <div class="row">
                        <form class="form-inline" style="width:100%">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="nickname" class="small col-4">From:</label>
                                    <input id="nickname" name="nickname" type="text" class="form-control form-control-sm col-8" value="">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="nickname" class="small col-4">To:</label>
                                    <input id="name" name="name" type="text" class="form-control form-control-sm col-8" value="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <button type="button" class="btn btn-primary btn-sm col-1 ml-1">Go</button>
                            </div>
                        </form>
                    </div>

                    <!-- Gemerate -->
  

                    <div class="row">
                        <div class="col-12 pt-3">

                          
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                <thead>                                    
                                    <tr>

                                     
                                        <th class="small text-center">Start Time</th>
                                        <th class="small text-center">Member Name</th>
                                        <th class="small text-center">Tutor Name</th>
                                        <th class="small text-center">Q1</th>
                                        <th class="small text-center">Q2</th>
                                        <th class="small text-center">Q3</th>
                                        <th class="small text-center">Q4</th>
                                        <th class="small text-center">Remarks</th>
                                
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th class="small text-center">2012/3/30 18:29</th>
                                        <th class="small text-center">testmember1, testmember1</th>
                                        <th class="small text-center">testtutor3</th>
                                        <th class="small text-center">GOOD</th>
                                        <th class="small text-center">AVERAGE</th>
                                        <th class="small text-center">BAD</th>
                                        <th class="small text-center">BAD</th>
                                        <th class="small text-center"></th>                              
                                    </tr>
                                </tbody>
                                </table>
                            </div>
                          
                        </div>
                    </div>
                </div>
            </div>
            <!--[end] card-->


        </div>




    </div>
</div>

</div>
@endsection
