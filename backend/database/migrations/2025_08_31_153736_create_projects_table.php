<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique(); // إضافة slug لعناوين URL صديقة لمحركات البحث
            $table->text('short_description')->nullable(); // وصف مختصر
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->json('technologies')->nullable(); // تقنيات مستخدمة في المشروع
            $table->string('project_url')->nullable(); // رابط المشروع الحي
            $table->string('github_url')->nullable(); // رابط مستودع GitHub
            $table->date('start_date')->nullable(); // تاريخ البدء
            $table->date('end_date')->nullable(); // تاريخ الانتهاء
            $table->boolean('is_published')->default(false); // حالة النشر
            $table->integer('sort_order')->default(0); // ترتيب الظهور
            $table->timestamps();
            $table->softDeletes(); // حذف мягкий
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
