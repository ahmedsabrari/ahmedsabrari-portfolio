<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تم تفعيل حسابك</title>
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
            border-bottom: 2px solid #4CAF50;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #4CAF50;
            margin: 0;
        }
        .content {
            margin-bottom: 30px;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #4CAF50;
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
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>تم تفعيل حسابك</h1>
        </div>
        
        <div class="content">
            <p>مرحباً {{ $user->name }},</p>
            
            <p>نود إعلامك أنه تم تفعيل حسابك مرة أخرى في منصة {{ $appName }}.</p>
            
            <p>يمكنك الآن تسجيل الدخول إلى حسابك والاستفادة من جميع الخدمات والميزات التي نقدمها كالمعتاد.</p>
            
            <p>نرحب بك مرة أخرى ونتطلع إلى تقديم أفضل تجربة ممكنة لك.</p>
            
            <div class="text-center mt-20">
                <a href="{{ $loginUrl }}" class="button">تسجيل الدخول الآن</a>
            </div>
            
            <p class="mt-20">إذا واجهتك أي صعوبات في الوصول إلى حسابك، فلا تتردد في التواصل مع فريق الدعم.</p>
        </div>
        
        <div class="footer">
            <p>مع تمنياتنا بتجربة ممتعة،<br>فريق {{ $appName }}</p>
            <p>© {{ date('Y') }} {{ $appName }}. جميع الحقوق محفوظة.</p>
        </div>
    </div>
</body>
</html>