@extends('layouts.adminScheduler')

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
                <div class="card-header esi-card-header">
                    Lesson
                </div>
                <div class="card-body">

                    <form name="dateForm" method="GET">
                    <div class="row">
                            <div class="col-6">
                                <label for="inputDate" class="pr-3">Date:</label>
                                <input type="date" id="inputDate" name="inputDate" value="{{ $dateToday }}" min="2000-01-01" class="inputDate hasDatepicker form-control form-control-sm d-inline col-3"> 
                                <input type="submit" class="btn btn-primary btn-sm d-inline col-1" value="Go">

                            </div>
                    </div>
                    </form>


                    
                    <div class="legend bg-lightgray mt-2">
                        <table cellspacing="0" cellpadding="5">
                            <tbody>
                                <tr>
                                    <td>Legend</td>
                                    <td>:</td>

                                    <td><img src="{{ url('images/iNothing.gif') }}" alt="Nothing" title="Nothing" align="absmiddle"> Nothing</td>
                                    <td><img src="{{ url('images/iTutorScheduled.gif') }}" alt="Tutor Scheduled" title="Tutor Scheduled" align="absmiddle"> Tutor Scheduled</td>
                                    <td><img src="{{ url('images/iClientReserved.gif') }}" alt="Client Reserved" title="Client Reserved" align="absmiddle"> Client Reserved</td>
                                    <td><img src="{{ url('images/iSuppressed.gif') }}" alt="Suppressed Schedule" title="Suppressed Schedule" align="absmiddle"> Suppressed Schedule</td>
                                </tr>
                                <tr>

                                    <td></td>
                                    <td></td>
                                    <td><img src="{{ url('images/iCompleted.gif') }}" alt="Completed" title="Completed" align="absmiddle"> Completed</td>
                                    <td><img src="{{ url('images/iTutorCancelled.gif') }}" alt="Tutor Cancelled" title="Tutor Cancelled" align="absmiddle"> Tutor Cancelled</td>
                                    <td><img src="{{ url('images/iNotAvailable.gif') }}" alt="Not Available" title="Not Available" align="absmiddle"> Client Not Available</td>
                                    <td></td>

                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <p class="card-text"></p>


                    <schedule-item-component
                        ref="scheduleItemComponent"
                        :scheduled_at="'{{ $dateToday }}'"
                        :schedule_next_day="'{{ $nextDay }}'"
                        :year="{{ $year}}" 
                        :month="{{ $month }}" 
                        :day="{{ $day}}"
                        :duration="{{ $shiftDuration }}"
                        :schedule_items ="{{ json_encode($scheduleItems) }}"
                        :tutors="{{ $tutors }}"
                        :members="{{ $members }}"
                        api_token="{{ Auth::user()->api_token }}"
                        csrf_token="{{ csrf_token() }}"
                    />

                </div>
            </div>
            <!--[end] card-->




        </div>
    </div>

</div>
@endsection

@section('scripts')
@parent
<script>
    let url = "{{ url('/admin/member/') }}";
    function openMemberTab(id) {       
       window.open(url + "/" + id,'memberTab');
    }
</script>
@endsection