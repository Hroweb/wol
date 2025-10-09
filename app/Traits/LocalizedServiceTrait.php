<?php

namespace App\Traits;

trait LocalizedServiceTrait
{
    public function resolveLocale(string $locale = null): array
    {
        return [$locale ?? app()->getLocale(), config('app.fallback_locale')];
    }

    protected function attachLocalized($model, string $locale, string $fallback, array $fields): void
    {
        $translation = $model->translations()->firstWhere('locale', $locale)
            ?? $model->translations()->firstWhere('locale', $fallback);

        $localized = [];

        foreach ($fields as $field) {
            $value = $translation?->$field ?? '';
            $localized[$field] = $value !== '' ? $value : 'No translation available';
        }

        $model->localized = $localized;
    }

    protected function attachRelatedLocalized($parentModel, string $relationName, string $locale, string $fallback, array $fields): void
    {
        $relatedModel = $parentModel->$relationName;

        if ($relatedModel) {
            $this->attachLocalized($relatedModel, $locale, $fallback, $fields);
        }
    }
}
