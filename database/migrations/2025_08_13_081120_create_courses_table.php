<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('academic_year', 50);      // e.g. "2024-2025"
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamps();

            $table->index(['start_date', 'end_date']);
        });

        Schema::create('course_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('locale', 5);               // 'en', 'hy', 'en-US'
            $table->string('title');
            $table->string('slug');                    // localized slug
            $table->text('description')->nullable();
            $table->string('curriculum_pdf_url')->nullable();
            $table->string('welcome_video_url')->nullable();

            $table->unique(['course_id', 'locale']);   // one translation per locale per course
            $table->unique(['slug', 'locale']);        // slug unique within locale
            $table->index('locale');

            $table->timestamps();                      // helpful for caches/audits
        });

        Schema::create('course_user', function (Blueprint $table) {
            $table->id();

            // Pivot FKs
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            // Application / enrollment fields (per user+course)
            $table->text('application_reason')->nullable();
            $table->boolean('applied_previously')->default(false);

            // Enrollment lifecycle
            $table->enum('status', [
                'pending',     // applied, waiting for review
                'enrolled',    // approved by admin
                'rejected',    // rejected by admin
                'completed',   // finished the course
                'dropped',     // left the course
                'cancelled',   // canceled
            ])->default('pending');

            $table->timestampTz('enrolled_at')->nullable();

            $table->timestamps();

            // A user should enroll once per course
            $table->unique(['course_id', 'user_id']);

            // Helpful indexes
            $table->index(['user_id', 'status']);
            $table->index(['course_id', 'status']);
        });
    }

    public function down(): void
    {
        // BP: Drop child table first
        Schema::dropIfExists('course_translations');
        Schema::dropIfExists('course_user');
        Schema::dropIfExists('courses');
    }
};
