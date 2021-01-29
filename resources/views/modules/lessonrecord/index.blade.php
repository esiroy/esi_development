@extends('layouts.esi-app')

@section('content')
<div class="container bg-light">

    <div class="esi-box">

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light ">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Lesson Record</li>
            </ol>
        </nav>

        <div class="container">

            <div class="row">
                <!--sidebar-->
                <div class="col-md-3">
                    <div>
                        @include('modules.member.sidebar.profile')
                    </div>
                    <div class="mt-3 mb-4">
                        @include('modules.member.sidebar.reports')
                    </div>
                </div>
                <!--[end sidebar]-->

                <div class="col-md-9">
                    <div class="card esi-card">
                        <div class="card-header esi-card-header">
                            受講履歴
                        </div>

                        <div class="card-body">
                            <p>これまで受講した一覧です。</p>

                            <table class="table table-bordered table-sm">
                                <tbody>
                                    <tr>
                                        <th class="small text-center bg-light text-dark font-weight-bold">Date</th>
                                        <th class="small text-center bg-light text-dark font-weight-bold">Tutor</th>
                                        <th class="small text-center bg-light text-dark font-weight-bold">View</th>
                                    </tr>
                                    @foreach ($reportcards as $reportcard)
                                    <tr>
                                        <td class="text-center">{{ \App\Models\ScheduleItem::find($reportcard->schedule_item_id)['lesson_time'] }}</td>
                                        <td class="text-center">
                                            @php
                                            $member = \App\Models\Member::where('user_id', $reportcard->member_id)->first();
                                            @endphp
                                            {{ $member->user->firstname ?? '' }} {{ $member->user->lastname ?? '' }}
                                        </td>
                                        <td class="text-center">
                                            <a href="reportcard/{{$reportcard->id}}">» 評価</a>
                                        </td>
                                    </tr>
                                    @endforeach

                                </tbody>
                            </table>

                            <div class="float-right mt-4">
                                <ul class="pagination pagination-sm">
                                    {{ $reportcards->appends(request()->query())->links() }}
                                </ul>
                            </div>

                        </div>
                    </div>

                    <div class="card esi-card mt-4">
                        <div class="card-header esi-card-header">
                            添削履歴
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-sm">
                                <thead>
                                    <tr>
                                        <th class="small text-center bg-light text-dark font-weight-bold">Date</th>
                                        <th class="small text-center bg-light text-dark font-weight-bold">Tutor</th>
                                        <th class="small text-center bg-light text-dark font-weight-bold">View</th>
                                    </tr>
                                </thead>
                                <tbody id="reportCardBody">
                                    @foreach ($datereportcards as $datereportcard)
                                    <tr>
                                        <td class="text-center">{{ $datereportcard->lesson_date }}</td>
                                        <td class="text-center">
                                            @php
                                            $member = \App\Models\Member::where('user_id', $datereportcard->member_id)->first();
                                            @endphp
                                            {{ $member->user->firstname ?? '' }} {{ $member->user->lastname ?? '' }}
                                        </td>
                                        <td class="text-center"><a href="userreportcarddate/{{$datereportcard->id}}">» 評価</a></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>


                            <div class="float-right mt-4">
                                <ul class="pagination pagination-sm">
                                    {{ $datereportcards->appends(request()->query())->links() }}
                                </ul>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>


    </div>


</div>
</div>
@endsection
