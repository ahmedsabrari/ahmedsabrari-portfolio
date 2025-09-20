<?php

namespace App\Console\Commands\Auth;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;

class HealthCheckCommand extends Command
{
    protected $signature = 'app:health-check';
    protected $description = 'فحص صحة التطبيق والخدمات';

    public function handle()
    {
        $this->info('بدء فحص صحة التطبيق...');

        $health = [
            'database' => $this->checkDatabase(),
            'redis' => $this->checkRedis(),
            'storage' => $this->checkStorage(),
            'environment' => $this->checkEnvironment(),
        ];

        $this->table(['الخدمة', 'الحالة', 'التفاصيل'], $health);

        $allHealthy = collect($health)->every(fn($item) => $item['الحالة'] === '✅');

        if ($allHealthy) {
            $this->info('جميع الخدمات تعمل بشكل طبيعي.');
            return 0;
        } else {
            $this->error('هناك مشاكل في بعض الخدمات.');
            return 1;
        }
    }

    protected function checkDatabase()
    {
        try {
            DB::connection()->getPdo();
            return ['الخدمة' => 'قاعدة البيانات', 'الحالة' => '✅', 'التفاصيل' => 'متصل'];
        } catch (\Exception $e) {
            return ['الخدمة' => 'قاعدة البيانات', 'الحالة' => '❌', 'التفاصيل' => $e->getMessage()];
        }
    }

    protected function checkRedis()
    {
        try {
            Redis::ping();
            return ['الخدمة' => 'Redis', 'الحالة' => '✅', 'التفاصيل' => 'متصل'];
        } catch (\Exception $e) {
            return ['الخدمة' => 'Redis', 'الحالة' => '❌', 'التفاصيل' => $e->getMessage()];
        }
    }

    protected function checkStorage()
    {
        try {
            Storage::disk('local')->put('healthcheck.txt', 'test');
            Storage::disk('local')->delete('healthcheck.txt');
            return ['الخدمة' => 'التخزين', 'الحالة' => '✅', 'التفاصيل' => 'يعمل'];
        } catch (\Exception $e) {
            return ['الخدمة' => 'التخزين', 'الحالة' => '❌', 'التفاصيل' => $e->getMessage()];
        }
    }

    protected function checkEnvironment()
    {
        try {
            $env = config('app.env');
            $debug = config('app.debug') ? 'تفعيل' : 'تعطيل';
            return ['الخدمة' => 'البيئة', 'الحالة' => '✅', 'التفاصيل' => "{$env} (التصحيح: {$debug})"];
        } catch (\Exception $e) {
            return ['الخدمة' => 'البيئة', 'الحالة' => '❌', 'التفاصيل' => $e->getMessage()];
        }
    }
}