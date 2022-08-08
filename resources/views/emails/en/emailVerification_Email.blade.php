<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Email Verification Mail</title>
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
            background-color: rgba(255, 140, 0, 0.795);
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
    <h1>Email Verification Mail</h1>
    <hr>
    Hey <span
        style="
        font-size: large;
        font-weight: 600;
        color: darkorange;
    ">{{ $name }}</span>!
    <p>Thank you for regist in our app,</p>
    <p>We hope that you will like our application.</p>
    <p>Please verify your email with bellow code : </p>
    <div class="code">{{ $code }}</div>
    <p>Copy this code and paste inside the app,</p>
    <p>Do not share this code with anyone.</p>
    <h4>Thanks,</h4>
    <h4>The Vigor team.</h4>
</body>

</html>
