<?php

namespace App\Services;

use App\Models\Teacher;
use App\Repositories\TeacherRepository;


class TeacherService
{
    public function __construct(private TeacherRepository $repo) {}

    public function list(int $perPage = 10, ?string $locale = null, $order=[]): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        [$loc, $fallback] = $this->resolveLocale($locale);
        $paginator = $this->repo->paginateWithTranslations($perPage, $fallback, $loc, $order);
        $paginator->getCollection()->transform(function (Teacher $t) use ($loc, $fallback) {
            $this->attachLocalized($t, $loc, $fallback);
            return $t;
        });
        return $paginator;
    }

    private function resolveLocale(?string $locale): array
    {
        return [$locale ?? app()->getLocale(), config('app.fallback_locale')];
    }

    private function attachLocalized(Teacher $teacher, string $locale, string $fallback): void
    {
        $translation = $teacher->translations()->firstWhere('locale', $locale)
            ?? $teacher->translations()->firstWhere('locale', $fallback);

        $teacher->localized = [
            'first_name' => $translation->first_name,
            'last_name' => $translation->last_name,
            'position' => $translation->position,
            'church_name' => $translation->church_name,
            'bio' => $translation?->bio,
            'country' => $translation?->country,
            'city' => $translation?->city,
            'specializations' => explode(',', $translation?->specializations) ?? [],
            //'specializations' => (array) $translation?->specializations ?? [],
        ];
    }
}
