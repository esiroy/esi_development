
<table id="report" cellspacing="0" cellpadding="0" style="margin-top:10px">
    <tr>
        <td colspan="3">Tutor Salary Report</div></td>
    </tr>
    <tr>
        <td style="width:150px">From: {{ $dateFrom }}</td>
        <td style="width:150px">To: {{ $dateTo }}</td>
        <td style="width:150px;text-align:right">Date: {{ $dateToday }}</td>
    </tr>
    <thead>
        <tr>
            <td style="border-top:1px solid #000;border-bottom:1px solid #000; width:35px">I.D.</td>
            <td style="border-top:1px solid #000;border-bottom:1px solid #000; width:100px">Tutor</td>
            <td style="border-top:1px solid #000;border-bottom:1px solid #000; width:90px">Shift</td>
            <td style="border-top:1px solid #000;border-bottom:1px solid #000; width:90px">Date</td>
            <td style="border-top:1px solid #000;border-bottom:1px solid #000; width:90px">Time</td>
            <td style="border-top:1px solid #000;border-bottom:1px solid #000; width:90px">Status</td>
            <td style="border-top:1px solid #000;border-bottom:1px solid #000; width:50px">Salary</td>
            <td style="border-top:1px solid #000;border-bottom:1px solid #000; width:50px">Cost</td>
        </tr>
    </thead>
    <tbody>
        @foreach($schedulesData as $schedule)
        <tr>
            <td>{{ $schedule['id'] }}</td>
            <td>{{ $schedule['tutor'] }}</td>
            <td>{{ $schedule['shift'] }}</td>
            <td>{{ $schedule['date ']}}</td>
            <td>{{ $schedule['time ']}}</td>
            <td>{{ $schedule['status'] }}</td>
            <td>{{ $schedule['salary'] }}</td>
            <td>{{ $schedule['cost ']}}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<style>
    table {
        width: 100%;
    }
    table#report td {
        font-size: 14px;
        text-align: center;
        padding-top:5px;
        padding-bottom: 5px;
    }
</style>
