<?php

namespace App\Services;

use App\Models\Course;
use App\Repositories\CourseRepository;

class CourseService
{
    public function __construct(private CourseRepository $repo) {}

    public function list(int $perPage = 10, ?string $locale = null, $order = []): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        [$loc, $fallback] = $this->resolveLocale($locale);
        $paginator = $this->repo->paginateWithTranslations($perPage, $fallback, $loc, $order);
        $paginator->getCollection()->transform(function (Course $c) use ($loc, $fallback) {
            $this->attachLocalized($c, $loc, $fallback);
            return $c;
        });
        return $paginator;
    }

    public function store(array $payload): Course
    {
        $base = [
            'academic_year' => $payload['academic_year'] ?? null,
            'start_date' => $payload['start_date'] ?? null,
            'end_date' => $payload['end_date'] ?? null,
        ];
        $translations = $payload['translations'] ?? [];
        return $this->repo->createWithTranslations($base, $translations);
    }

    public function update(Course $course, array $payload): Course
    {
        $base = [
            'academic_year' => $payload['academic_year'] ?? null,
            'start_date' => $payload['start_date'] ?? null,
            'end_date' => $payload['end_date'] ?? null,
        ];
        $translations = $payload['translations'] ?? [];
        return $this->repo->updateWithTranslations($course, $base, $translations);
    }

    private function resolveLocale(?string $locale): array
    {
        return [$locale ?? app()->getLocale(), config('app.fallback_locale')];
    }

    private function attachLocalized(Course $course, string $locale, string $fallback): void
    {
        $translation = $course->translations()->firstWhere('locale', $locale)
            ?? $course->translations()->firstWhere('locale', $fallback);

        $course->localized = [
            'title' => $translation->title,
            'description' => $translation->description,
            'slug' => $translation->slug,
        ];
    }
}
