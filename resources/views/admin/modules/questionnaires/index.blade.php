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
                <li class="breadcrumb-item active" aria-current="page">Questionnaires</li>
            </ol>
        </nav>


        <div class="container">
            <!--[start card] -->
            <div class="card esi-card">
                <div class="card-header esi-card-header">
                    Questionnaire List
                </div>
                <div class="card-body">

                    <!--Search-->
                    <div class="row">                        
                        <form class="form-inline" style="width:100%" method="GET">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="date_from" class="small col-4">From:</label>
                                    <input id="date_from" name="date_from" type="date" class="form-control form-control-sm col-8" 
                                    value="{{ request()->has('date_from') ? request()->get('date_from') : '' }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="date_to" class="small col-4">To:</label>
                                    <input id="date_to" name="date_to" type="date" class="form-control form-control-sm col-8" 
                                    value="{{ request()->has('date_to') ? request()->get('date_to') : '' }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary btn-sm col-1 ml-1">Go</button>
                            </div>
                        </form>
                    </div>

                    <!-- Gemerate -->
  

                    <div class="row">
                        <div class="col-12 pt-3">

                            <div class="float-right">
                                <ul class="pagination pagination-sm">
                                    <small class="mr-4 pt-2">Page :</small>                                     
                                    {{ $questionnaires->appends(request()->query())->links() }}
                                </ul>
                            </div>

                            <div class="table-responsive ">
                                <table class="table table-bordered table-sm table-striped">
                                <thead>
                                    <tr>
                                        <th class="small text-center bg-light text-dark font-weight-bold">Start Time</th>
                                        <th class="small text-center bg-light text-dark font-weight-bold">Member Name</th>
                                        <th class="small text-center bg-light text-dark font-weight-bold">Tutor Name</th>
                                        <th class="small text-center bg-light text-dark font-weight-bold">Q1</th>
                                        <th class="small text-center bg-light text-dark font-weight-bold">Q2</th>
                                        <th class="small text-center bg-light text-dark font-weight-bold">Q3</th>
                                        <th class="small text-center bg-light text-dark font-weight-bold">Q4</th>
                                        <th class="small text-center bg-light text-dark font-weight-bold">Remarks</th>                                
                                    </tr>                                   
                                </thead>
                                <tbody>
                                    @foreach($questionnaires as $questionnaire)
                                    <tr>
                                        <td class="small text-center">{{ $questionnaire->created_at }}</td>
                                        <td class="small text-center">
                                            @php 
                                                $memberUser = \App\Models\User::where('id', $questionnaire->member_id)->first() 
                                            @endphp

                                            {{ $memberUser['first_name'] . ", " . $memberUser['last_name'] }}
                                            </td>
                                        <td class="small text-center">{{ $questionnaire->tutor_id  }}</td>

                                        @php
                                            $questionnaireItems = \App\Models\QuestionnaireItem::where('questionnaire_id', $questionnaire->id)
                                                                        ->orderBy('id', 'asc')->get();
                                        @endphp

                                        @foreach($questionnaireItems as $item)
                                            <td class="small text-center">
                                                <!--<small>{{ $item->question }}</small>-->
                                                {{ $item->grade }}
                                            </td> 
                                        @endforeach
                                        <td class="small text-center" style="width:300px">{{ $questionnaire->remarks }}</td>                              
                                    </tr>
                                     @endforeach
                                </tbody>
                                </table>

                                <div class="float-right mt-4">
                                    <ul class="pagination pagination-sm">
                                        <small class="mr-4 pt-2">
                                        Page :</small>
                                        {{ $questionnaires->appends(request()->query())->links() }}
                                    </ul>
                                </div>



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