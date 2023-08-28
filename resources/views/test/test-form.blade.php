<!DOCTYPE html>
<html>
<head>
    <title>Test Form</title>
</head>
<body>
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('process-form') }}">
        @csrf
        <label for="data">Enter Large Amount of Data:</label>
        <textarea name="data" id="data" rows="10" cols="30"></textarea>
        <br>
        <button type="submit">Submit</button>
    </form>
</body>
</html>
