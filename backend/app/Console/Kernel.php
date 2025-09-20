<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        \App\Console\Commands\Auth\CreateAdminCommand::class,
        \App\Console\Commands\Auth\UserBlockCommand::class,
        \App\Console\Commands\Auth\UserUnblockCommand::class,
        \App\Console\Commands\Auth\UserStatsCommand::class,
        \App\Console\Commands\Auth\CleanTokensCommand::class,
        \App\Console\Commands\Auth\GenerateSitemapCommand::class,
        \App\Console\Commands\Auth\BackupDatabaseCommand::class,
        \App\Console\Commands\Auth\ClearLogsCommand::class,
        \App\Console\Commands\Auth\HealthCheckCommand::class,
        \App\Console\Commands\Auth\MyCustomCommand::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        // تنظيف التوكنات المنتهية أسبوعياً في منتصف الليل
        $schedule->command('tokens:clean --expired')
                 ->weekly()
                 ->at('00:00')
                 ->onOneServer()
                 ->appendOutputTo(storage_path('logs/schedule.log'));

        // إنشاء نسخة احتياطية يومياً في الساعة 2 صباحاً
        $schedule->command('db:backup --compress')
                 ->dailyAt('02:00')
                 ->onOneServer()
                 ->appendOutputTo(storage_path('logs/backup.log'));

        // تحديث خريطة الموقع يومياً في الساعة 3 صباحاً
        $schedule->command('sitemap:generate')
                 ->dailyAt('03:00')
                 ->onOneServer()
                 ->appendOutputTo(storage_path('logs/sitemap.log'));

        // تنظيف الملفات المؤقتة القديمة يومياً
        $schedule->command('clean:temp-files')
                 ->daily()
                 ->onOneServer();

        // تنظيف سجلات التطبيق القديمة (احتفظ بـ 7 أيام فقط)
        $schedule->command('log:clear --keep=7')
                 ->daily()
                 ->onOneServer();

        // مراقبة صحة التطبيق كل 30 دقيقة
        $schedule->command('app:health-check')
                 ->everyThirtyMinutes()
                 ->onOneServer();
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

    /**
     * الحصول على وقتzone التطبيق للجدولة
     */
    protected function scheduleTimezone()
    {
        return config('app.timezone', 'UTC');
    }
}