<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تغيير في حالة حسابك</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 2px solid #f44336;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #f44336;
            margin: 0;
        }
        .content {
            margin-bottom: 30px;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #f44336;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }
        .footer {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            color: #777;
            font-size: 14px;
        }
        .text-center {
            text-align: center;
        }
        .mt-20 {
            margin-top: 20px;
        }
        .reason-box {
            background-color: #fff3f3;
            border: 1px solid #ffcdd2;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>تغيير في حالة حسابك</h1>
        </div>
        
        <div class="content">
            <p>عزيزي/عزيزتي {{ $user->name }},</p>
            
            <p>نود إعلامك أنه تم تعطيل حسابك في منصة {{ $appName }} بناءً على سياسات استخدام المنصة.</p>
            
            @if($reason)
            <div class="reason-box">
                <strong>سبب التعطيل:</strong>
                <p>{{ $reason }}</p>
            </div>
            @endif
            
            <p>لن تتمكن من الوصول إلى حسابك أو استخدام خدمات المنصة حتى يتم فك الحظر من قبل المسؤول.</p>
            
            <p>إذا كنت تعتقد أن هذا الإجراء تم عن طريق الخطأ، أو إذا كنت ترغب في معرفة المزيد عن سبب هذا الإجراء، يرجى التواصل مع فريق الدعم.</p>
            
            <div class="text-center mt-20">
                <a href="{{ $contactUrl }}" class="button">اتصل بالدعم</a>
            </div>
            
            <p class="mt-20">شكراً لتفهمك واهتمامك بمراجعة سياسات استخدام المنصة.</p>
        </div>
        
        <div class="footer">
            <p>مع التحية،<br>فريق {{ $appName }}</p>
            <p>© {{ date('Y') }} {{ $appName }}. جميع الحقوق محفوظة.</p>
        </div>
    </div>
</body>
</html>