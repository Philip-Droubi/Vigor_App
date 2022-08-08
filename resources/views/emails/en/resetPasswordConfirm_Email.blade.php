<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>change Password Notification</title>
    <style>
        body{
            font-family: sans-serif;
        }
        .danger {
            color: red;
            font-weight: 600;
            text-transform: uppercase;
        }

        @media (min-width:600px) {
            p {
                max-width: 600px;
            }
        }
    </style>
</head>
<body>
    <h1>change Password Notification</h1><hr>
    Hey <span style=" 
        font-size: large;
        font-weight: 600;
        color: darkorange;
    ">{{$name}}</span>!
    <p>Your account's password has been reset at : {{$time}}</p>
    <p><span class="danger"> If </span> you are not the one who change it, please sign-in into your account and reset your password with stronger one as soon as possible.</p>
    <h4>Thanks,</h4>
    <h4>The Vigor team.</h4>
</body>
</html>