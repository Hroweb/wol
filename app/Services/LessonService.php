<?php

namespace App\Services;

use App\Models\Lesson;
use App\Repositories\LessonRepository;
use App\Traits\LocalizedServiceTrait;

class LessonService
{
    use LocalizedServiceTrait;
    public function __construct(private readonly LessonRepository $repo) {}

    public function list(int $perPage = 10, ?string $locale = null, $order=[]): \Illuminate\Pagination\LengthAwarePaginator
    {
        [$loc, $fallback] = $this->resolveLocale($locale);
        $paginator = $this->repo->paginateWithTranslations($perPage, $fallback, $loc, $order);
        $paginator->through(function (Lesson $l) use ($loc, $fallback) {
            $this->attachLocalized($l, $loc, $fallback, ['title', 'description', 'materials']);
            $this->attachRelatedLocalized($l, 'course', $loc, $fallback, ['title', 'description', 'slug']);
            return $l;
        });
        return $paginator;
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
