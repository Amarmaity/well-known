<!DOCTYPE html>
<html>

<head>
    <title>Anniversary Congrats</title>
</head>

<body>
    <h2>Hi {{ $user->fname }} {{ $user->lname }},</h2>

    <p>Congratulations on completing {{ $completedYears }} {{ $completedYears == 1 ? 'year' : 'years' }} with us!</p>
    <p>We appreciate your contributions and dedication.</p>

    <p>Wishing you continued success at {{ config('app.name') }}.</p>

    <br>
    <p>Best regards,</p>
</body>

</html>
