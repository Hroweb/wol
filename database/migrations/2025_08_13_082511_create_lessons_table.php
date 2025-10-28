<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1) Lessons (base, language-independent)
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->date('lesson_date');
            $table->unsignedInteger('sort_order')->default(1);
            $table->timestamps();

            $table->index(['course_id', 'lesson_date']);
        });

        // 2) Lesson translations (per-locale title/description/materials)
        Schema::create('lesson_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->constrained('lessons')->cascadeOnDelete();
            $table->string('locale', 5);           // 'en', 'hy', ...
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('materials')->nullable(); // e.g., URLs (newline separated)
            $table->timestamps();

            $table->unique(['lesson_id', 'locale']);
            $table->index('locale');
        });

        // 3) Lesson parts (per-part teacher/media)
        Schema::create('lesson_parts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->constrained('lessons')->cascadeOnDelete();

            // Teachers are in a separate table; a part MUST have a teacher.
            $table->foreignId('teacher_id')->constrained('teachers')->restrictOnDelete();

            $table->unsignedTinyInteger('part_number');          // 1 or 2
//            $table->text('audio_file_urls')->nullable();         // one or more URLs
            $table->unsignedInteger('duration_minutes')->nullable();
            $table->timestamps();

            // Ensure one row per part for a lesson
            $table->unique(['lesson_id', 'part_number']);
            $table->index(['teacher_id']);
        });

        Schema::create('lesson_part_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_part_id')->constrained('lesson_parts')->cascadeOnDelete();
            $table->string('locale', 5);
            $table->string('audio_file')->nullable();
            $table->timestamps();

            $table->unique(['lesson_part_id', 'locale']);
            $table->index('locale');
        });

        Schema::create('audio_upload_jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->nullable()->constrained('lessons')->cascadeOnDelete();
            $table->foreignId('lesson_part_id')->nullable()->constrained('lesson_parts')->cascadeOnDelete();
            $table->string('locale', 5);
            $table->string('original_filename');
            $table->string('temp_path');
            $table->string('final_path')->nullable();
            $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->text('error_message')->nullable();
            $table->integer('file_size')->nullable();
            $table->timestamps();

            $table->index(['lesson_id', 'status']);
            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        // Drop children first
        Schema::dropIfExists('lesson_parts');
        Schema::dropIfExists('lesson_translations');
        Schema::dropIfExists('lessons');
        Schema::dropIfExists('audio_upload_jobs');

    }
};
