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
                max-width: -600px;
            }
        }
    </style>
</head>
<body>
    <h1 dir="rtl">تأكيد تغيير كلمة المرور</h1><hr>
    <p dir="rtl">مرحبا <span style=" 
        font-size: large;
        font-weight: 600;
        color: darkorange;
    ">{{$name}}</span>!</p>
    <p dir="rtl">لقد تم تغيير كلمة مرور حسابك في {{$time}},</p>
    <p dir="rtl">إذا لم تكن أنت من قام بهذا الرجاء الدخول إلى حسابك وتغيير كلمة المرور لأخرى أقوى في أسرع وقت ممكن.</p>
    <h4 dir="rtl">شكراً,</h4>
    <h4 dir="rtl">فريق تطبيق Vigor .</h4>
</body>
</html>