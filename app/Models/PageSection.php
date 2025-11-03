<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PageSection extends Model
{
    use HasFactory;

    // Available section types
    const SECTION_TYPES = [
        'hero',
        'video',
        'featured_teachers',
        'featured_courses',
    ];

    protected $fillable = [
        'page_id',
        'section_type',
        'order',
        'settings',
        'is_active',
    ];

    protected $casts = [
        'settings' => 'array',
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    // Relationships
    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    public function translations(): HasMany
    {
        return $this->hasMany(PageSectionTranslation::class);
    }

    // Helper: get translation for a locale
    public function t(?string $locale = null): ?PageSectionTranslation
    {
        $locale ??= app()->getLocale();
        $this->loadMissing('translations');
        return $this->translations->firstWhere('locale', $locale)
            ?? $this->translations->firstWhere('locale', config('app.fallback_locale'));
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('section_type', $type);
    }
}
