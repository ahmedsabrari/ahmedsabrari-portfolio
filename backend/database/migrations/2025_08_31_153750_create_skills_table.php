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
        Schema::create('skills', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category')->default('general'); // تصنيف المهارات (frontend, backend, etc.)
            $table->integer('level')->default(0);
            $table->string('color')->nullable(); // لون لعرض المهارة
            $table->string('icon')->nullable(); // أيقونة تمثل المهارة
            $table->boolean('is_featured')->default(false); // مهارة مميزة
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skills');
    }
};
