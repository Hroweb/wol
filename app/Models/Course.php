<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Course extends Model
{
    use HasFactory;
    protected $fillable = ['academic_year','start_date','end_date'];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    // Translations
    public function translations(): HasMany
    {
        return $this->hasMany(CourseTranslation::class);
    }

    // Helper: get translation for a locale (falls back to app fallback)
    public function t(?string $locale = null): ?CourseTranslation
    {
        $locale ??= app()->getLocale();
        $this->loadMissing('translations');
        return $this->translations->firstWhere('locale', $locale)
            ?? $this->translations->firstWhere('locale', config('app.fallback_locale'));
    }

    // Lessons
    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class)->orderBy('lesson_date');
    }

    // Enrolled users (pivot)
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot(['status','application_reason','applied_previously','enrolled_at'])
            ->withTimestamps();
    }

    // Derived status (optional helper; not stored)
    public function getStatusAttribute(): string
    {
        $now = now()->startOfDay();
        if ($now->lt($this->start_date)) return 'upcoming';
        if ($now->gt($this->end_date))   return 'completed';
        return 'active';
    }

    // Scopes for filtering by derived status
    public function scopeUpcoming($q) { return $q->where('start_date', '>', now()); }
    public function scopeActive($q)   { return $q->where('start_date','<=',now())->where('end_date','>=',now()); }
    public function scopeCompleted($q){ return $q->where('end_date','<',now()); }
}
