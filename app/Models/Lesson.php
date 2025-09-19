<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lesson extends Model
{
    use HasFactory;
    protected $fillable = ['course_id','lesson_date','sort_order'];

    protected $casts = ['lesson_date' => 'date'];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function translations(): HasMany
    {
        return $this->hasMany(LessonTranslation::class);
    }

    public function parts(): HasMany
    {
        return $this->hasMany(LessonPart::class)->orderBy('part_number');
    }

    // Helper to get a translation by locale (with fallback)
    public function t(?string $locale = null): ?LessonTranslation
    {
        $locale ??= app()->getLocale();
        $this->loadMissing('translations');
        return $this->translations->firstWhere('locale', $locale)
            ?? $this->translations->firstWhere('locale', config('app.fallback_locale'));
    }
}
