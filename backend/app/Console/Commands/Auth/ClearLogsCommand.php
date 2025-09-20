<?php

namespace App\Console\Commands\Auth;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class ClearLogsCommand extends Command
{
    protected $signature = 'log:clear {--keep=30 : عدد الأيام للاحتفاظ بالسجلات}';
    protected $description = 'حذف سجلات التطبيق القديمة';

    public function handle()
    {
        $keepDays = $this->option('keep');
        $logPath = storage_path('logs');
        $cutoffDate = Carbon::now()->subDays($keepDays);

        $files = File::files($logPath);
        $deletedCount = 0;

        foreach ($files as $file) {
            if ($file->getExtension() === 'log' && 
                Carbon::createFromTimestamp($file->getMTime())->lessThan($cutoffDate)) {
                File::delete($file->getPathname());
                $deletedCount++;
            }
        }

        $this->info("تم حذف {$deletedCount} ملف سجل قديم.");
        $this->info("تم الاحتفاظ بالسجلات من آخر {$keepDays} أيام.");

        return 0;
    }
}