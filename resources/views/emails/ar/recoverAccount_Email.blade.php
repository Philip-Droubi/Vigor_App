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
                margin-right: 100px;
            }

            p {
                max-width: -600px;
            }
        }
    </style>
</head>

<body>
    <h1 dir="rtl">البريد الخاص باستعادة الحساب</h1>
    <hr>
    <p dir="rtl"> أهلاً بعودتك <span
            style="
        font-size: large;
        font-weight: 600;
        color: darkorange;
    ">{{ $name }}</span>!
    <p dir="rtl">نحن سعداء جدا لأنك عدت لاستخدام تطبيقنا, كل ما عليك فعله هو تأكيد هذه العملية من خلال الرمز التالي:
    </p>
    <div class="code">{{ $code }}</div>
    <p dir="rtl">
        ستنتهي صلاحية هذا الرمز تلقائياً بعد 20 دقيقة من طلب إعادة تعيين كلمة المرور,
        سيتوجب عليك طلب رمز جديد بعد إنقضاء هذه المدة .
    </p>
    <h4 dir="rtl">شكراً,</h4>
    <h4 dir="rtl">فريق تطبيق Vigor .</h4>
</body>

</html>
