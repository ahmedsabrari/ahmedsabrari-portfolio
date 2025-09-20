<?php

namespace App\Console\Commands\Auth;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemapCommand extends Command
{
    protected $signature = 'sitemap:generate';

    protected $description = 'إنشاء/تحديث خريطة الموقع';

    public function handle()
    {
        $sitemap = Sitemap::create();

        // إضافة الروابط الثابتة
        $sitemap->add(Url::create('/')
            ->setPriority(1.0)
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY));

        $sitemap->add(Url::create('/projects')
            ->setPriority(0.9)
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY));

        $sitemap->add(Url::create('/skills')
            ->setPriority(0.8)
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY));

        // حفظ خريطة الموقع
        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('تم إنشاء خريطة الموقع بنجاح في: ' . public_path('sitemap.xml'));

        return 0;
    }
}