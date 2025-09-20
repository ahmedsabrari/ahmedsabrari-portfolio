<?php

namespace App\Console\Commands\Auth;

use Illuminate\Console\Command;
use Laravel\Sanctum\PersonalAccessToken;

class CleanTokensCommand extends Command
{
    protected $signature = 'tokens:clean {--expired : حذف التوكنات المنتهية فقط}';

    protected $description = 'تنظيف توكنات Sanctum';

    public function handle()
    {
        $query = PersonalAccessToken::query();

        if ($this->option('expired')) {
            $query->where('expires_at', '<', now());
            $count = $query->count();
            $query->delete();
            
            $this->info("تم حذف {$count} توكن منتهي الصلاحية");
        } else {
            $count = $query->count();
            $query->delete();
            
            $this->info("تم حذف {$count} توكن");
        }

        return 0;
    }
}