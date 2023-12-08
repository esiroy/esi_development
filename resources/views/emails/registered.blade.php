<div class="container">
    Dear {{ $data->firstname }} {{ $data->lastname }}
    <div style="margin-top:20px; font-size: 14px">
        Here are your My Tutor account details:
    </div>
    <div style="margin-top:20px; font-size: 14px">
        <div>Username: {{ $data->username }}</div>
        <div>Password: {{ $data->password }}</div>

        <div style="margin-top:20px; font-size: 14px">
            Login here link: {{ url('/login') }}
        </div>

        <div style="margin-top:20px; font-size: 14px">
            Please keep your account information confidential. You may change your password after you login.
        </div>

        <div style="margin-top:20px; font-size: 14px">
            <div>Thank you very much,</div>
            <div>The My Tutor Team</div>
        </div>
    </div>
</div>
