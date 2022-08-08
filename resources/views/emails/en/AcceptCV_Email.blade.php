<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Accept CV</title>
    <style>
        body {
            font-family: sans-serif;
        }
    </style>
</head>

<body>
    <h1>Your CV Accepted</h1>
    <hr>
    Hey <span
        style="
        font-size: large;
        font-weight: 600;
        color: darkorange;
    ">{{ $name }}</span>!
    <p>We have accepted your request to join our team as a {{ $role }}, We are very happy to have you in the
        vigor app team .
    </p>
    <p>You have been given all {{ $role }} abilities to use .</p>
    <h4>We hope you are up to the responsibility given.</h4>
    <h4>Thanks,</h4>
    <h4>The Vigor team.</h4>
</body>

</html>
