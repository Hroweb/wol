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

            // Create lesson parts with translations
            $this->createLessonParts($lesson, $parts);

            return $lesson->load(['translations', 'parts.translations', 'parts.teacher']);
        });
    }

    public function getCreateFormData(): array
    {
        $courses = Course::with('translations')->get();
        $teachers = Teacher::with('translations')->get();

        // Process teachers data for JavaScript with translations
        $locales = \App\Helpers\Helper::getLocales();
        $teachersData = $teachers->map(function($teacher) use ($locales) {
            $names = [];
            foreach ($locales as $locale => $label) {
                $translation = $teacher->translations->firstWhere('locale', $locale);
                $names[$locale] = ($translation ? $translation->first_name . ' ' . $translation->last_name : '') ?: 'Teacher ' . $teacher->id;
            }

            return [
                'id' => $teacher->id,
                'name' => $names
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
                $arr = [
                    'locale' => $t['locale'],
                    'title' => $t['title'] ?? '',
                    'description' => $t['description'] ?? '',
                    //'materials' => isset($t['materials']) ? json_encode($t['materials']) : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                if(isset($t['materials'])) $arr['materials'] = json_encode($t['materials']);
                return $arr;
            })->all();
    }

    private function prepareLessonParts(array $parts): array
    {
        return collect($parts)
            ->map(function ($part) {
                return [
                    'teacher_id' => $part['teacher_id'],
                    'part_number' => $part['part_number'],
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

            // Update translations properly (update existing, create new, delete removed)
            $this->updateLessonTranslations($lesson, $translations);

            // Update lesson parts and their translations
            $this->updateLessonParts($lesson, $parts);

            return $lesson->load(['translations', 'parts.translations', 'parts.teacher']);
        });
    }

    private function updateLessonTranslations(Lesson $lesson, array $translations): void
    {
        // Get existing translations
        $existingTranslations = $lesson->translations()->get()->keyBy('locale');

        foreach ($translations as $translationData) {
            $locale = $translationData['locale'];

            if ($existingTranslations->has($locale)) {
                // Update existing translation
                $translation = $existingTranslations[$locale];
                $translation->update([
                    'title' => $translationData['title'] ?? '',
                    'description' => $translationData['description'] ?? '',
                    'materials' => isset($translationData['materials']) ? json_encode($translationData['materials']) : $translation->materials,
                ]);
            } else {
                // Create new translation
                $lesson->translations()->create([
                    'locale' => $locale,
                    'title' => $translationData['title'] ?? '',
                    'description' => $translationData['description'] ?? '',
                    'materials' => isset($translationData['materials']) ? json_encode($translationData['materials']) : null,
                ]);
            }
        }

        // Delete translations that are no longer in the data
        $newLocales = collect($translations)->pluck('locale')->toArray();
        $lesson->translations()->whereNotIn('locale', $newLocales)->delete();
    }

    private function queryWithTranslations(string $locale, string $fallback): \Illuminate\Database\Eloquent\Builder
    {
        return Lesson::with(['translations' => function ($q) use ($locale, $fallback) {
            $q->whereIn('locale', [$locale, $fallback]);
        }]);
    }

    private function createLessonParts(Lesson $lesson, array $parts): void
    {
        if (empty($parts)) {
            return;
        }

        foreach ($parts as $partData) {
            // Create the lesson part
            $lessonPart = $lesson->parts()->create($this->makePartAttributes($partData));

            // Create translations for this part
            $this->syncPartTranslations($lessonPart, $partData['translations'] ?? []);
        }
    }

    private function updateLessonParts(Lesson $lesson, array $parts): void
    {
        if (empty($parts)) {
            return;
        }

        // Get existing parts
        $existingParts = $lesson->parts()->with('translations')->get()->keyBy('part_number');

        foreach ($parts as $partData) {
            $partNumber = $partData['part_number'];

            if ($existingParts->has($partNumber)) {
                // Update existing part
                $lessonPart = $existingParts[$partNumber];
                $lessonPart->update($this->makePartAttributes($partData));

                // Update translations
                $this->syncPartTranslations($lessonPart, $partData['translations'] ?? []);
            } else {
                // Create new part
                $lessonPart = $lesson->parts()->create($this->makePartAttributes($partData));

                // Create translations for this part
                $this->syncPartTranslations($lessonPart, $partData['translations'] ?? []);
            }
        }

        // Delete parts that are no longer in the data
        $newPartNumbers = collect($parts)->pluck('part_number')->toArray();
        $lesson->parts()->whereNotIn('part_number', $newPartNumbers)->delete();
    }

    private function makePartAttributes(array $partData): array
    {
        return [
            'teacher_id' => $partData['teacher_id'],
            'part_number' => $partData['part_number'],
            'duration_minutes' => $partData['duration_minutes'] ?? null,
        ];
    }

    private function syncPartTranslations(\App\Models\LessonPart $lessonPart, array $translations): void
    {
        // Replace all translations for the part with provided set
        $lessonPart->translations()->delete();
        foreach ($translations as $locale => $translationData) {
            $lessonPart->translations()->create([
                'locale' => $locale,
                'audio_file' => $translationData['audio_file'] ?? null,
            ]);
        }
    }
}
