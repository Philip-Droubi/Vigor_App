<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reset Password Notification</title>
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

        .danger {
            color: red;
            font-weight: 600;
            text-transform: uppercase;
        }

        @media (min-width:600px) {
            .code {
                margin-right: 100px;
            }

            p {
                max-width: -600px;
            }
        }
    </style>
</head>

<body>
    <h1 dir="rtl">إشعار إعادة تعيين كلمة المرور</h1><hr>
    <p dir="rtl">مرحبا <span style=" 
        font-size: large;
        font-weight: 600;
        color: darkorange;
    ">{{$name}}</span>!</p>
    <p dir="rtl">لقد تلقينا طلب إعادة تعيين كلمة مرور من حسابك,</p>
    <p dir="rtl"> الرجاء تأكيد بريدك الإلكتروني عن طريق الرمز التالي</p>
    <div class="code">{{$token}}</div>
    <p dir="rtl">إذا لم تكن أنت من قدم هذا الطلب لا تقم بمشاركة هذا الرمز مع أي شخص.</p>
    <p dir="rtl">
        ستنتهي صلاحية هذا الرمز تلقائياً بعد 20 دقيقة من طلب إعادة تعيين كلمة المرور,
        سيتوجب عليك طلب رمز جديد بعد إنقضاء هذه المدة .
    </p>
    <h4 dir="rtl">شكراً,</h4>
    <h4 dir="rtl">فريق تطبيق Vigor .</h4>
</body>

</html>