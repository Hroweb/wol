<?php

namespace App\Repositories;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Teacher;
use Illuminate\Support\Facades\DB;

class LessonRepository
{
    protected function translatableSortable(): array
    {
        return ['title', 'description'];
    }

    protected function baseSortable(): array
    {
        return ['lesson_date', 'created_at', 'id'];
    }

    public function paginateWithTranslations(
        int $perPage,
        string $locale,
        string $fallback,
        array $order
    ): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $q = Lesson::query();
        $needsJoin = collect($order)->contains(function ($item) {
            $key = $item['key'] ?? '';
            return in_array($key, $this->translatableSortable(), true);
        });

        if($needsJoin) {
            // for locale
            $q->leftJoin('lesson_translations as l_loc', function ($join) use ($locale) {
                $join->on('l_loc.lesson_id', '=', 'lessons.id')->where('l_loc.locale', '=', $locale);
            } );
            // for fallback if locale is not provided
            $q->leftJoin('lesson_translations as l_fb', function ($join) use ($fallback) {
                $join->on('l_fb.lesson_id', '=', 'lessons.id')->where('l_fb.locale', '=', $fallback);
            });
        }

        $q->select('lessons.*');

        if(!empty($order)) {
            foreach ($order as $orderValue) {
                $key = strtolower($orderValue['key'] ?? '');
                $dir = strtolower($orderValue['dir'] ?? 'asc') === 'desc' ? 'desc' : 'asc';

                if (in_array($key, $this->translatableSortable(), true)) {
                    // translated-only fields: coalesce locale -> fallback (no lessons.<col>)
                    $q->orderByRaw("COALESCE(l_loc.{$key}, l_fb.{$key}, '') {$dir}");
                    continue;
                }

                if (in_array($key, $this->baseSortable(), true)) {
                    $q->orderBy("lessons.{$key}", $dir);
                    continue;
                }

                // Fallback default
                $q->orderBy('lessons.created_at', 'desc');

                // Non-translatable (e.g. lesson_date, sort_order, created_at, id...)
                $q->orderBy("lessons.{$key}", $dir);
            }
        }

        // Default order if nothing provided
        if (empty($order)) {
            $q->latest('lessons.created_at');
        }

        return $q->with(['course.translations'])->paginate($perPage)->withQueryString();
    }

    public function createWithTranslations(array $baseData, array $translations): Lesson
    {
        return DB::transaction(function () use ($baseData, $translations) {
            /** @var Lesson $lesson */
            $lesson = Lesson::create($baseData);
            $prepared = $this->prepareTranslations($translations);
            $lesson->translations()->createMany($prepared);
            return $lesson->load('translations');
        });
    }

    public function updateWithTranslations(Lesson $lesson, array $baseData, array $translations): Lesson
    {
        return DB::transaction(function () use ($lesson, $baseData, $translations) {
            $lesson->update($baseData);

            // Delete existing translations
            $lesson->translations()->delete();

            $prepared = $this->prepareTranslations($translations);
            $lesson->translations()->createMany($prepared);
            return $lesson->load('translations');
        });
    }

    public function createWithTranslationsAndParts(array $baseData, array $translations, array $parts): Lesson
    {
        return DB::transaction(function () use ($baseData, $translations, $parts) {
            // Create the lesson
            $lesson = Lesson::create($baseData);

            // Create translations
            $preparedTranslations = $this->prepareTranslations($translations);
            $lesson->translations()->createMany($preparedTranslations);

            // Create lesson parts
            $preparedParts = $this->prepareLessonParts($parts);

            $lesson->parts()->createMany($preparedParts);

            return $lesson->load(['translations', 'parts.teacher']);
        });
    }

    public function getCreateFormData(): array
    {
        $courses = Course::with('translations')->get();
        $teachers = Teacher::with('translations')->get();

        // Process teachers data for JavaScript
        $teachersData = $teachers->map(function($teacher) {
            $translation = $teacher->translations->firstWhere('locale', 'en');
            return [
                'id' => $teacher->id,
                'name' => ($translation ? $translation->first_name . ' ' . $translation->last_name : '') ?: 'Teacher ' . $teacher->id
            ];
        })->toArray();

        return [
            'courses' => $courses,
            'teachers' => $teachers,
            'teachersData' => $teachersData
        ];
    }

    private function prepareTranslations(array $translations): array
    {
        return collect($translations)
            ->map(function ($t) {
                return [
                    'locale' => $t['locale'],
                    'title' => $t['title'] ?? '',
                    'description' => $t['description'] ?? null,
                    'materials' => $t['materials'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })->all();
    }

    private function prepareLessonParts(array $parts): array
    {
        return collect($parts)
            ->map(function ($part) {
                return [
                    'teacher_id' => $part['teacher_id'],
                    'part_number' => $part['part_number'],
                    'audio_file_urls' => $part['audio_file_urls'] ?? null,
                    'duration_minutes' => $part['duration_minutes'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })->all();
    }

    public function updateWithTranslationsAndParts(Lesson $lesson, array $baseData, array $translations, array $parts): Lesson
    {
        return DB::transaction(function () use ($lesson, $baseData, $translations, $parts) {
            // Update the lesson
            $lesson->update($baseData);

            // Delete existing translations and parts
            $lesson->translations()->delete();
            $lesson->parts()->delete();

            // Create new translations
            $preparedTranslations = $this->prepareTranslations($translations);
            $lesson->translations()->createMany($preparedTranslations);

            // Create new lesson parts
            $preparedParts = $this->prepareLessonParts($parts);
            $lesson->parts()->createMany($preparedParts);

            return $lesson->load(['translations', 'parts.teacher']);
        });
    }

    private function queryWithTranslations(string $locale, string $fallback): \Illuminate\Database\Eloquent\Builder
    {
        return Lesson::with(['translations' => function ($q) use ($locale, $fallback) {
            $q->whereIn('locale', [$locale, $fallback]);
        }]);
    }
}
