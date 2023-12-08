<p>Tutor Salary Report</p>

<table>    
    <tr>
        <td style="width:150px">From: {{ $dateFrom }}</td>
        <td style="width:150px">To: {{ $dateTo }}</td>
        <td style="width:150px;text-align:right">Date: {{ $dateToday }}</td>
    </tr>
</table>


<table id="report" cellspacing="0" cellpadding="0" style="margin-top:10px">
    <thead>
        <tr>
            <td style="border-top:1px solid #000;border-bottom:1px solid #000; width:35px">I.D.</td>
            <td style="border-top:1px solid #000;border-bottom:1px solid #000; width:90px">Date</td>
            <td style="border-top:1px solid #000;border-bottom:1px solid #000; width:90px">Time</td>
            <td style="border-top:1px solid #000;border-bottom:1px solid #000; width:90px">Status</td>
            <td style="border-top:1px solid #000;border-bottom:1px solid #000; width:90px">Shift</td>
            <td style="border-top:1px solid #000;border-bottom:1px solid #000; width:100px">Agent</td>
            <td style="border-top:1px solid #000;border-bottom:1px solid #000; width:100px">Tutor</td>            
            <td style="border-top:1px solid #000;border-bottom:1px solid #000; width:100px">Member</td>
        </tr>
    </thead>
    <tbody>
        @foreach($schedulesData as $schedule)
        <tr>
            <td>{{ $schedule['id'] }}</td>
            <td>{{ $schedule['date']}}</td>
            <td>{{ $schedule['time']}}</td>
            <td>{{ $schedule['status'] }}</td>
            <td>{{ $schedule['shift'] }}</td>
            <td>{{ $schedule['agent']}}</td>
            <td>{{ $schedule['tutor'] }}</td>
            <td>{{ $schedule['member'] }}</td>            
        </tr>
        @endforeach
    </tbody>
</table>

<style>
    table {
        width: 100%;
    }
    table#report td {
        font-size: 12px;
        text-align: center;
        padding-top:5px;
        padding-bottom: 5px;
    }
</style>
