<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'template',
        'is_published',
        'order',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'order' => 'integer',
    ];

    // Translations
    public function translations(): HasMany
    {
        return $this->hasMany(PageTranslation::class);
    }

    // Helper: get translation for a locale (falls back to app fallback)
    public function t(?string $locale = null): ?PageTranslation
    {
        $locale ??= app()->getLocale();
        $this->loadMissing('translations');
        return $this->translations->firstWhere('locale', $locale)
            ?? $this->translations->firstWhere('locale', config('app.fallback_locale'));
    }

    // Sections
    public function sections(): HasMany
    {
        return $this->hasMany(PageSection::class)->orderBy('order');
    }

    // Active sections only
    public function activeSections(): HasMany
    {
        return $this->hasMany(PageSection::class)
            ->where('is_active', true)
            ->orderBy('order');
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeBySlug($query, string $slug)
    {
        return $query->where('slug', $slug);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}

