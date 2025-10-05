<?php

namespace App\Services;

use App\Models\Lesson;
use App\Repositories\LessonRepository;

class LessonService
{
    public function __construct(private LessonRepository $repo) {}

    public function list(int $perPage = 10, ?string $locale = null, $order=[]): \Illuminate\Pagination\LengthAwarePaginator
    {
        [$loc, $fallback] = $this->resolveLocale($locale);
        $paginator = $this->repo->paginateWithTranslations($perPage, $fallback, $loc, $order);
        $paginator->through(function (Lesson $l) use ($loc, $fallback) {
            $this->attachLocalized($l, $loc, $fallback);
            $this->attachCourseLocalized($l, $loc, $fallback);
            return $l;
        });
        return $paginator;
    }

    private function resolveLocale(?string $locale): array
    {
        return [$locale ?? app()->getLocale(), config('app.fallback_locale')];
    }

    private function attachLocalized(Lesson $lesson, string $locale, string $fallback): void
    {
        $translation = $lesson->translations()->firstWhere('locale', $locale)
            ?? $lesson->translations()->firstWhere('locale', $fallback);

        $lesson->localized = [
            'title' => $translation->title ?? '',
            'description' => $translation->description ?? '',
            'materials' => $translation->materials ?? '',
        ];
    }

    private function attachCourseLocalized(Lesson $lesson, string $locale, string $fallback): void
    {
        if ($lesson->course) {
            $courseTranslation = $lesson->course->translations()->firstWhere('locale', $locale)
                ?? $lesson->course->translations()->firstWhere('locale', $fallback);

            $lesson->course->localized = [
                'title' => $courseTranslation->title ?? '',
                'description' => $courseTranslation->description ?? '',
                'slug' => $courseTranslation->slug ?? '',
            ];
        }
    }

    public function getCreateData(): array
    {
        return $this->repo->getCreateFormData();
    }

    public function store(array $payload): Lesson
    {
        $base = [
            'course_id' => $payload['course_id'],
            'lesson_date' => $payload['lesson_date'],
        ];

        $translations = $payload['translations'] ?? [];
        $parts = $payload['lesson_parts'] ?? [];

        return $this->repo->createWithTranslationsAndParts($base, $translations, $parts);
    }

    public function update(Lesson $lesson, array $payload): Lesson
    {
        $base = [
            'course_id' => $payload['course_id'],
            'lesson_date' => $payload['lesson_date'],
        ];

        $translations = $payload['translations'] ?? [];
        $parts = $payload['lesson_parts'] ?? [];

        return $this->repo->updateWithTranslationsAndParts($lesson, $base, $translations, $parts);
    }
}
