<!DOCTYPE html>
<html>

<head>
    <title>Evaluation Credentials</title>
</head>

<body>
    <h2>Hello {{ $user->fname }} {{ $user->lname }}</h2>

    <p>You have been granted access for {{ config('app.name') }}.</p>

    <p><strong>Your evaluation login credentials are as follows:</strong></p>
    <ul>
        <li><strong>Email:</strong> {{ $user->email }}</li>
        <li><strong>Password:</strong> {{ $password }}</li>
    </ul>

    <p>You can log in using the link below:</p>
    <p><a href="{{ $loginUrl }}">{{ $loginUrl }}</a></p>

    <p style="color:#d9534f; font-weight:bold;">
        🔒 Security Notice
    </p>

    <p>
        For security reasons, we strongly recommend changing your password
        immediately after your first login. Please keep your login credentials
        confidential and do not share them with anyone.
    </p>

    <br>
    <p>Regards,<br>
        Delostyle Team</p>
</body>

</html>
