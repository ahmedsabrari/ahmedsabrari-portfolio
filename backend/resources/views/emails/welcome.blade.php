<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مرحباً بك في {{ $appName }}</title>
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
            <h1>مرحباً بك في {{ $appName }}</h1>
        </div>
        
        <div class="content">
            <p>مرحباً {{ $user->name }},</p>
            
            <p>نحن سعداء جداً بانضمامك إلى عائلة {{ $appName }}. لقد تم إنشاء حسابك بنجاح وأنت الآن جزء من مجتمعنا.</p>
            
            <p>يمكنك الآن الاستفادة من جميع الميزات والخدمات التي نقدمها:</p>
            
            <ul>
                <li>تصفح المحتوى الحصري</li>
                <li>تفاعل مع المجتمع</li>
                <li>استفد من الخدمات المميزة</li>
                <li>والمزيد...</li>
            </ul>
            
            <div class="text-center mt-20">
                <a href="{{ $loginUrl }}" class="button">ابدأ رحلتك الآن</a>
            </div>
            
            <p class="mt-20">إذا كان لديك أي استفسار أو تحتاج إلى مساعدة، لا تتردد في التواصل مع فريق الدعم الخاص بنا.</p>
        </div>
        
        <div class="footer">
            <p>مع تمنياتنا لك بتجربة ممتعة،<br>فريق {{ $appName }}</p>
            <p>© {{ date('Y') }} {{ $appName }}. جميع الحقوق محفوظة.</p>
        </div>
    </div>
</body>
</html>