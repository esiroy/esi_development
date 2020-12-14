@extends('layouts.esi-app')


@section('content')


<div class="container bg-light">

    <div class="esi-box">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light ">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">My Page</li>
            </ol>
        </nav>


        <div class="container">
            <!--[start card] -->
            <div class="card esi-card">
                <div class="card-header esi-card-header">講師スケジュール </div>
                <div class="card-body">

                    <div class="mt-2 mb-4">
                        <div>予約= 予約可能です</div>
                        <div>済　= 本人で予約済みです</div>
                    </div>

                    <!--[start] Update Date -->
                    <form name="dateForm" method="GET">
                        <div class="row mt-3 mb-3">
                            <div class="col-md-3">
                                <label for="dateToday" class="pr-0">Date:</label>
                                <input type="date" id="dateToday" name="dateToday" value="{{ $dateToday }}" min="2000-01-01" class="inputDate hasDatepicker form-control form-control-sm d-inline col-7">
                                <input type="submit" class="btn btn-primary btn-sm form-control form-control-sm d-inline col-3" value="Go">
                            </div>
                        </div>
                    </form><!--[end] Update Date -->

                    <div class="card-header esi-card-header-title text-center">
                        {{ date('Y', strtotime($dateToday)) }} 年 {{ date('m', strtotime($dateToday)) }}月 {{ date('d', strtotime($dateToday)) }}日
                    </div>

                    <div class="card-body scrollable-x p-0">
                        <table class="table table-bordered table-schedules">
                            <tr>
                                <td class="schedTime"></td>
                                @foreach($lessonSlots as $lessonSlot)
                                <td class="schedTime">
                                    <div>
                                        <div class="text-small">{{ $lessonSlot['startTime'] }}</div>
                                        <div class="text-small">{{ $lessonSlot['endTime'] }}</div>
                                    </div>                                    
                                </td>
                                @endforeach        
                            </tr>            

                                                
                            @foreach($tutors as $tutor)
                            <tr>
                                <!--[start] Tutor Information-->
                                <td>
                                    <div style="width:125px">
                                        <small>{{ $tutor->name_en }}</small>
                                    </div>
                                </td>


            

                                @foreach($lessonSlots as $lessonSlot) 
                                <td>               
                                    @php 
                                        $startTimePH = date('h:i', strtotime($lessonSlot['startTime'] ." - 1 hour "));
                                    @endphp
                                    @foreach ($lessons as $lesson)
                                        @if(isset($lesson[$startTimePH])) 
                                            <a href="#">reserve tutor</a>
                                        @endif
                                    @endforeach                                    
                                </td>
                                @endforeach     
                           
                            </tr>               
                            @endforeach  

                        </table>
                    </div>





                </div>
            </div>
        </div>

     

    </div>

</div>
@endsection