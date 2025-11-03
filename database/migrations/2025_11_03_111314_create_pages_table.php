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
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();               // e.g. "home", "about", "contact"
            $table->string('template')->default('default'); // e.g. "default", "home", "contact"
            $table->boolean('is_published')->default(false);
            $table->integer('order')->default(0);
            $table->timestamps();

            $table->index('is_published');
            $table->index('order');
        });

        Schema::create('page_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('locale', 5);                    // 'en', 'ar', etc.
            $table->string('title');
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->longText('content')->nullable();        // Rich text/HTML for simple pages

            $table->unique(['page_id', 'locale']);          // one translation per locale per page
            $table->index('locale');

            $table->timestamps();
        });

        Schema::create('page_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('section_type');                 // 'hero', 'video', 'text_block', etc.
            $table->integer('order')->default(0);
            $table->json('settings')->nullable();           // Non-translatable settings (video_url, IDs, etc.)
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['page_id', 'order']);
            $table->index('section_type');
        });

        Schema::create('page_section_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_section_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('locale', 5);
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->longText('content')->nullable();        // HTML/text content
            $table->string('cta_text')->nullable();         // Call-to-action button text
            $table->string('cta_link')->nullable();         // Call-to-action button link

            $table->unique(['page_section_id', 'locale']);
            $table->index('locale');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_section_translations');
        Schema::dropIfExists('page_sections');
        Schema::dropIfExists('page_translations');
        Schema::dropIfExists('pages');
    }
};
