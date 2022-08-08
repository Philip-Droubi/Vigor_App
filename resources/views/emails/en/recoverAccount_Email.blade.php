<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Recover Account</title>
    <style>
        body {
            font-family: sans-serif;
        }

        .code {
            justify-self: center;
            max-width: 200px;
            padding: 13px 15px;
            text-align: center;
            border-radius: 9px;
            color: whitesmoke;
            text-transform: uppercase;
            background-color: #1976d2;
            box-shadow: 0px 4px 9px 0px #3860cf9e;
            transition: 0.2s;
            font-weight: bold;
            letter-spacing: 1px;
            margin: auto;
        }

        .code:hover {
            box-shadow: 0px 0px 0px 0px #3860cf00;
            padding: 13px 20px;
            border-radius: 4px;
        }

        .code::selection {
            background-color: #16324e;
        }

        @media (min-width:600px) {
            .code {
                margin-left: 100px;
            }

            p {
                max-width: 600px;
            }
        }
    </style>
</head>

<body>
    <h1>Recover account Email</h1>
    <hr>
    Welcome back <span
        style="
        font-size: large;
        font-weight: 600;
        color: darkorange;
    ">{{ $name }}</span>!
    <p>
        We are so glad you are back to using our app, all you have to do is confirm this process with the following
        code:
    </p>
    <div class="code">{{ $code }}</div>
    <p>
        The code will automaticlly expire after 20 minutes from your password reset request,
        you will need to request a new code after this time has passed.
    </p>
    <h4>Thanks,</h4>
    <h4>The Vigor team.</h4>
</body>

</html>
