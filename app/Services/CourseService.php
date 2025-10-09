<?php

namespace App\Services;

use App\Models\Course;
use App\Repositories\CourseRepository;
use App\Traits\LocalizedServiceTrait;

class CourseService
{
    use LocalizedServiceTrait;
    public function __construct(private readonly CourseRepository $repo) {}

    public function list(int $perPage = 10, ?string $locale = null, $order = []): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        [$loc, $fallback] = $this->resolveLocale($locale);
        $paginator = $this->repo->paginateWithTranslations($perPage, $fallback, $loc, $order);
        $paginator->through(function (Course $c) use ($loc, $fallback) {
            $this->attachLocalized($c, $loc, $fallback, ['title', 'description', 'slug']);
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
}
