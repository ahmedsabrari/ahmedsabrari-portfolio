<?php

namespace App\Console\Commands\Auth;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class BackupDatabaseCommand extends Command
{
    protected $signature = 'db:backup {--compress : ضغط النسخة الاحتياطية}';

    protected $description = 'إنشاء نسخة احتياطية من قاعدة البيانات';

    public function handle()
    {
        $filename = 'backup-' . Carbon::now()->format('Y-m-d-H-i-s') . '.sql';
        $path = storage_path('app/backups/' . $filename);
        
        // التأكد من وجود مجلد النسخ الاحتياطية
        if (!file_exists(storage_path('app/backups'))) {
            mkdir(storage_path('app/backups'), 0755, true);
        }

        $command = sprintf(
            'mysqldump --user=%s --password=%s --host=%s --port=%s %s > %s',
            config('database.connections.mysql.username'),
            config('database.connections.mysql.password'),
            config('database.connections.mysql.host'),
            config('database.connections.mysql.port'),
            config('database.connections.mysql.database'),
            $path
        );

        $returnVar = null;
        $output = null;
        exec($command, $output, $returnVar);

        if ($returnVar !== 0) {
            $this->error('فشل في إنشاء النسخة الاحتياطية');
            return 1;
        }

        // ضغط الملف إذا طلب
        if ($this->option('compress')) {
            $compressedPath = $path . '.gz';
            exec("gzip -c {$path} > {$compressedPath}");
            unlink($path);
            $path = $compressedPath;
            $filename .= '.gz';
        }

        $this->info('تم إنشاء النسخة الاحتياطية بنجاح: ' . $filename);
        $this->info('المسار: ' . $path);

        return 0;
    }
}