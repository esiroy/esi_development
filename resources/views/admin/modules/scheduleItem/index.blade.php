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
                        <table>
                            <tr>
                                <td style="width:60px">
                                    <small>Date</small>
                                </td>
                                <td style="width:150px">
                                    <input type="date" id="inputDate" name="inputDate" placeholder="{{ $dateToday }}" value="{{ $dateToday }}" min="2000-01-01" data-date-format="YYYY年 M月 DD日" class="inputDate form-control form-control-sm col-sm-12 col-md-12">
                                </td>
                                <td>
                                  <input type="submit" class="btn btn-primary btn-sm" value="Go">
                                </td>
                            </tr>
                        </table>
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


                    <schedule-item-component ref="scheduleItemComponent" 
                        :scheduled_at="'{{ $dateToday }}'" 
                        :schedule_next_day="'{{ $nextDay }}'" 
                        :year="{{ $year}}" :month="{{ $month }}" :day="{{ $day}}" 
                        :duration="{{ $shiftDuration }}" 
                        :schedule_items="{{ json_encode($scheduleItems) }}" 
                        :tutors="{{ $tutors }}" :members="{{ $members }}" 
                        api_token="{{ Auth::user()->api_token }}" 
                        csrf_token="{{ csrf_token() }}" />

                </div>
            </div>
            <!--[end] card-->




        </div>
    </div>

</div>
@endsection



@section('styles')
@parent
<style>
    input.inputDate {
        overflow: hidden;
    }

    input.inputDate:before {
        content: attr(data-date);
    }

    input.inputDate::-webkit-datetime-edit,
    input.inputDate::-webkit-inner-spin-button,
    input.inputDate::-webkit-clear-button {
        display: none;
    }

    input.inputDate::-webkit-calendar-picker-indicator {
        position: absolute;
        top: 3px;
        right: 0;
        color: black;
        opacity: 1;
    }

</style>
@endsection



@section('scripts')
@parent

<script>
    let url = "{{ url('/admin/member/') }}";

    function openMemberTab(id) {
        window.open(url + "/" + id, 'memberTab');
    }

</script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.3/moment.min.js"></script>
<script type="text/javascript">
    window.addEventListener('load', function() 
    {        
        $(".inputDate").on("change", function() {
            this.setAttribute("data-date", moment(this.value, "YYYY-MM-DD").format(this.getAttribute("data-date-format")))
        }).trigger("change")
    });

</script>
@endsection
