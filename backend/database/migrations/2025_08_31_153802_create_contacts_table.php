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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('subject')->nullable(); // موضوع الرسالة
            $table->text('message');
            $table->string('ip_address')->nullable(); // IP المرسل
            $table->timestamp('read_at')->nullable(); // وقت قراءة الرسالة
            $table->json('form_data')->nullable(); // بيانات إضافية من النموذج
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
