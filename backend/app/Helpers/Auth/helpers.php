<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

if (!function_exists('app_name')) {
    /**
     * الحصول على اسم التطبيق
     */
    function app_name(): string
    {
        return config('app.name', 'Laravel');
    }
}

if (!function_exists('generate_unique_code')) {
    /**
     * توليد رمز فريد
     */
    function generate_unique_code(int $length = 8, string $prefix = ''): string
    {
        do {
            $code = $prefix . Str::upper(Str::random($length));
        } while (\App\Models\User::where('code', $code)->exists());

        return $code;
    }
}

if (!function_exists('format_date')) {
    /**
     * تنسيق التاريخ حسب المنطقة
     */
    function format_date($date, string $format = 'Y-m-d H:i:s', string $timezone = null): string
    {
        if (!$date instanceof \Carbon\Carbon) {
            $date = \Carbon\Carbon::parse($date);
        }

        if ($timezone) {
            $date->setTimezone($timezone);
        }

        return $date->format($format);
    }
}

if (!function_exists('arabic_date')) {
    /**
     * تحويل التاريخ إلى العربية
     */
    function arabic_date($date): string
    {
        $months = [
            'January' => 'يناير',
            'February' => 'فبراير',
            'March' => 'مارس',
            'April' => 'أبريل',
            'May' => 'مايو',
            'June' => 'يونيو',
            'July' => 'يوليو',
            'August' => 'أغسطس',
            'September' => 'سبتمبر',
            'October' => 'أكتوبر',
            'November' => 'نوفمبر',
            'December' => 'ديسمبر'
        ];

        $days = [
            'Saturday' => 'السبت',
            'Sunday' => 'الأحد',
            'Monday' => 'الاثنين',
            'Tuesday' => 'الثلاثاء',
            'Wednesday' => 'الأربعاء',
            'Thursday' => 'الخميس',
            'Friday' => 'الجمعة'
        ];

        $date = \Carbon\Carbon::parse($date);
        
        $day = $days[$date->format('l')];
        $month = $months[$date->format('F')];
        $dayNumber = $date->format('d');
        $year = $date->format('Y');

        return "{$day}، {$dayNumber} {$month} {$year}";
    }
}

if (!function_exists('sanitize_input')) {
    /**
     * تنظيف بيانات الإدخال
     */
    function sanitize_input($input)
    {
        if (is_array($input)) {
            return array_map('sanitize_input', $input);
        }

        return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('generate_slug')) {
    /**
     * توليد slug من النص
     */
    function generate_slug(string $text, string $separator = '-'): string
    {
        // تحويل النص إلى حروف لاتينية إذا كان عربياً
        $text = \Transliterator::create('Any-Latin; Latin-ASCII;')->transliterate($text);
        
        // تحويل إلى حروف صغيرة وإزالة الأحرف الخاصة
        $text = preg_replace('~[^\pL\d]+~u', $separator, $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, $separator);
        $text = preg_replace('~-+~', $separator, $text);
        $text = strtolower($text);

        return $text ?: Str::random(10);
    }
}

if (!function_exists('calculate_age')) {
    /**
     * حساب العمر من تاريخ الميلاد
     */
    function calculate_age($birthDate): int
    {
        return \Carbon\Carbon::parse($birthDate)->age;
    }
}

if (!function_exists('format_file_size')) {
    /**
     * تنسيق حجم الملف
     */
    function format_file_size(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}

if (!function_exists('generate_otp')) {
    /**
     * توليد رمز OTP
     */
    function generate_otp(int $length = 6): string
    {
        return Str::random($length, '1234567890');
    }
}

if (!function_exists('is_admin')) {
    /**
     * التحقق إذا كان المستخدم مسؤولاً
     */
    function is_admin($user = null): bool
    {
        if (!$user) {
            $user = auth()->user();
        }

        if (!$user) {
            return false;
        }

        return $user->role === 'admin' || $user->hasRole('admin');
    }
}

if (!function_exists('log_error')) {
    /**
     * تسجيل خطأ في السجل
     */
    function log_error(string $message, \Throwable $exception = null, array $context = []): void
    {
        Log::error($message, [
            'exception' => $exception ? [
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTraceAsString()
            ] : null,
            'context' => $context,
            'user_id' => auth()->id(),
            'url' => request()->fullUrl(),
            'ip' => request()->ip()
        ]);
    }
}

if (!function_exists('log_activity')) {
    /**
     * تسجيل نشاط في السجل
     */
    function log_activity(string $description, array $properties = [], string $logName = 'default'): void
    {
        if (class_exists(\Spatie\Activitylog\Models\Activity::class)) {
            activity($logName)
                ->withProperties($properties)
                ->log($description);
        } else {
            Log::info('Activity: ' . $description, $properties);
        }
    }
}

if (!function_exists('get_gravatar')) {
    /**
     * الحصول على صورة Gravatar
     */
    function get_gravatar(string $email, int $size = 80, string $default = 'mp'): string
    {
        $hash = md5(strtolower(trim($email)));
        
        return "https://www.gravatar.com/avatar/{$hash}?s={$size}&d={$default}";
    }
}

if (!function_exists('generate_random_color')) {
    /**
     * توليد لون عشوائي
     */
    function generate_random_color(): string
    {
        return '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
    }
}

if (!function_exists('array_to_csv')) {
    /**
     * تحويل مصفوفة إلى تنسيق CSV
     */
    function array_to_csv(array $data, string $delimiter = ',', string $enclosure = '"'): string
    {
        $output = fopen('php://temp', 'r+');
        
        foreach ($data as $row) {
            fputcsv($output, $row, $delimiter, $enclosure);
        }
        
        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);
        
        return $csv;
    }
}

if (!function_exists('validate_saudi_phone')) {
    /**
     * التحقق من صحة رقم الهاتف السعودي
     */
    function validate_saudi_phone(string $phone): bool
    {
        // تنسيق رقم الهاتف السعودي: 05xxxxxxxx
        return preg_match('/^05[0-9]{8}$/', $phone) === 1;
    }
}

if (!function_exists('format_saudi_phone')) {
    /**
     * تنسيق رقم الهاتف السعودي
     */
    function format_saudi_phone(string $phone): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        if (strlen($phone) === 9 && strpos($phone, '5') === 0) {
            $phone = '0' . $phone;
        }
        
        if (strlen($phone) === 10 && strpos($phone, '05') === 0) {
            return preg_replace('/(\d{2})(\d{3})(\d{3})(\d{2})/', '$1 $2 $3 $4', $phone);
        }
        
        return $phone;
    }
}

if (!function_exists('get_client_ip')) {
    /**
     * الحصول على IP العميل
     */
    function get_client_ip(): string
    {
        $ip = request()->ip();
        
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            return $ip;
        }
        
        // معالجة IP في حالة استخدام Cloudflare أو موازن الحمل
        if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
            $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $ip = trim($ips[0]);
        } elseif (!empty($_SERVER['HTTP_X_REAL_IP'])) {
            $ip = $_SERVER['HTTP_X_REAL_IP'];
        }
        
        return $ip;
    }
}

if (!function_exists('generate_qr_code')) {
    /**
     * توليد رمز QR
     */
    function generate_qr_code(string $data, int $size = 200): string
    {
        try {
            return \SimpleSoftwareIO\QrCode\Facades\QrCode::size($size)->generate($data);
        } catch (\Exception $e) {
            log_error('Failed to generate QR code', $e, ['data' => $data]);
            return '';
        }
    }
}

if (!function_exists('is_json')) {
    /**
     * التحقق إذا كان النص JSON صالحاً
     */
    function is_json(string $string): bool
    {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }
}

if (!function_exists('array_keys_exists')) {
    /**
     * التحقق من وجود عدة مفاتيح في المصفوفة
     */
    function array_keys_exists(array $keys, array $array): bool
    {
        return count(array_intersect_key(array_flip($keys), $array)) === count($keys);
    }
}

if (!function_exists('percentage')) {
    /**
     * حساب النسبة المئوية
     */
    function percentage(float $part, float $whole, int $decimals = 2): float
    {
        if ($whole == 0) {
            return 0;
        }
        
        return round(($part / $whole) * 100, $decimals);
    }
}