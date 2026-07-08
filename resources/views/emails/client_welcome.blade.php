<!DOCTYPE html>
<html>
<head>
    <title>Welcome to Evalon</title>
</head>
<body>
    <h2>Hello {{ $client->client_name }}</h2>

    <p>Welcome to Evalon. Your client account has been created.</p>

    <p><strong>Your login credentials are:</strong></p>
    <ul>
        <li><strong>Email:</strong> {{ $client->client_email }}</li>
        <li><strong>Password:</strong> {{ $password }}</li>
    </ul>

    <p>You can log in using the link below:</p>
    <p><a href="{{ $loginUrl }}">{{ $loginUrl }}</a></p>

    <br>
    <p>Regards,<br>Delostyle Team</p>
</body>
</html>