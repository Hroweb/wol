<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('photo')->nullable();
            $table->string('email')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->string('social_ig')->nullable();
            $table->string('social_youtube')->nullable();
            $table->timestamps();
        });

        // 2) Teacher translations (per-locale text fields)
        Schema::create('teacher_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('teachers')->cascadeOnDelete();
            $table->string('locale', 5); // 'en', 'hy', etc.

            $table->string('first_name');
            $table->string('last_name');
            $table->text('bio')->nullable();
            $table->text('specializations')->nullable();
            $table->string('position', 150)->nullable();
            $table->string('church_name', 150)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('country', 100)->nullable();

            $table->timestamps();

            // one translation per teacher per locale
            $table->unique(['teacher_id', 'locale']);
            $table->index('locale');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_translations');
        Schema::dropIfExists('teachers');
    }
};
