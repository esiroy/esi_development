@extends('layouts.admin')

@section('content')
<div class="container bg-light px-0">

    <div class="bg-lightblue2">
        <div class="container px-0">
            <nav class="submenu nav nav-pills flex-column flex-sm-row">
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-left border-primary" href="{{ url('admin/questionnaires') }}">Questionnaires</a>
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary" href="{{ url('admin/announcement') }}">Message</a>
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary" href="{{ url('admin/accounts') }}">Accounts</a>
                <a class="flex-sm text-sm-center nav-link font-weight-bold rounded-0 border-right border-primary active" href="{{ url('admin/course') }}">Material</a>
                <a class="flex-sm text-sm-center nav-link text-white font-weight-bold rounded-0 border-right border-primary" href="{{ url('admin/company') }}">Company</a>
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
                    Materials
                </div>
                <div class="card-body">

                    <div class="row">
                        <div class="col-12 pt-3">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                <thead>                                    
                                    <tr>

                                        <th class="small text-center">Course</th>
                                        <th class="small text-center">Parent</th>
                                        <th class="small text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th class="small text-center">                                           
                                            <div class="font-weight-bold">Lorem epsum Lorem epsum Lorem epsum dollor</div>
                                        </th>
                                        <th class="small text-center">Category 1</th>
                                        <th class="small text-center">
                                            <a href="#">Edit</a> | <a href="#">Sort</a> | <a href="#">Delete</a>
                                        </th>                             
                                    </tr>
                                    <tr>
                                        <th class="small text-center">                                           
                                            <div class="font-weight-bold">epsum dollor</div>
                                        </th>
                                        <th class="small text-center">Category 2</th>
                                        <th class="small text-center">
                                            <a href="#">Edit</a> | <a href="#">Sort</a> | <a href="#">Delete</a>
                                        </th>                             
                                    </tr>
                                    <tr>
                                        <th class="small text-center">                                           
                                            <div class="font-weight-bold">Lorem </div>
                                        </th>
                                        <th class="small text-center">Category 3</th>
                                        <th class="small text-center">
                                            <a href="#">Edit</a> | <a href="#">Sort</a> | <a href="#">Delete</a>
                                        </th>                             
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
