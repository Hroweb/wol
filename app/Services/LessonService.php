<?php

namespace App\Services;

use App\Models\Lesson;
use App\Repositories\LessonRepository;
use App\Traits\LocalizedServiceTrait;
//use App\Services\FileUploadService;

class LessonService
{
    use LocalizedServiceTrait;
    public function __construct(
        private readonly LessonRepository $repo,
        private readonly FileUploadService $uploadService,
    ) {}

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

    public function getEditData(Lesson $lesson): array
    {
        // base data
        $data = $this->repo->getCreateFormData();
        $lesson = $lesson->load(['translations', 'parts.translations', 'parts.teacher']);

        $lessonPartsData = $this->processLessonPartsData($lesson);
        $lessonTranslationsData = $this->processLessonTranslationsData($lesson);
        $teachersData = $this->processTeachersData($data['teachers']);

        return [
            'lesson' => $lesson,
            'lessonPartsData' => $lessonPartsData,
            'lessonTranslationsData' => $lessonTranslationsData,
            'courses' => $data['courses'],
            'teachers' => $data['teachers'],
            'teachersData' => $teachersData
        ];
    }

    private function processLessonPartsData(Lesson $lesson): array
    {
        return $lesson->parts->map(function($part) {
            $translations = [];
            foreach ($part->translations as $translation) {
                $translations[$translation->locale] = [
                    'audio_file' => $translation->audio_file ?? ''
                ];
            }

            return [
                'id' => $part->id,
                'teacher_id' => (string)$part->teacher_id,
                'part_number' => $part->part_number,
                'duration_minutes' => $part->duration_minutes ?? '',
                'translations' => $translations
            ];
        })->toArray();
    }

    private function processLessonTranslationsData(Lesson $lesson): array
    {
        $lessonTranslationsData = [];
        foreach ($lesson->translations as $translation) {
            // Ensure materials is always an array
            $materialsFiles = $translation->materials;
            if (is_string($materialsFiles)) {
                $materialsFiles = json_decode($materialsFiles, true) ?? [];
            }
            if (!is_array($materialsFiles)) {
                $materialsFiles = [];
            }

            $lessonTranslationsData[$translation->locale] = [
                'title' => $translation->title,
                'description' => $translation->description,
                'materials' => $materialsFiles,
            ];
        }
        return $lessonTranslationsData;
    }

    private function processTeachersData($teachers): array
    {
        $locales = \App\Helpers\Helper::getLocales();
        return $teachers->map(function($teacher) use ($locales) {
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
    }

    public function store(array $payload): Lesson
    {
        $base = $this->extractBaseData($payload);
        $translations = $this->processTranslations($payload['translations'] ?? []);
        $parts = $this->processParts($payload['lesson_parts'] ?? []);

        return $this->repo->createWithTranslationsAndParts($base, $translations, $parts);
    }

    public function update(Lesson $lesson, array $payload): Lesson
    {
        $base = $this->extractBaseData($payload);
        $existingMaterials = $this->getExistingMaterials($lesson);
        $translations = $this->processTranslations($payload['translations'] ?? [], $existingMaterials);
        $parts = $this->processParts($payload['lesson_parts'] ?? []);

        return $this->repo->updateWithTranslationsAndParts($lesson, $base, $translations, $parts);
    }

    private function extractBaseData(array $payload): array
    {
        return [
            'course_id' => $payload['course_id'],
            'lesson_date' => $payload['lesson_date'],
        ];
    }

    private function processTranslations(array $translations, array $existingMaterials = []): array
    {
        return $this->uploadService->uploadMaterials($translations, $existingMaterials);
    }

    private function processParts(array $parts): array
    {
        return $this->uploadService->uploadAudio($parts);
    }

    private function getExistingMaterials(Lesson $lesson): array
    {
        $existingMaterials = [];
        foreach ($lesson->translations as $translation) {
            $materials = $translation->materials;
            if (is_string($materials)) {
                $materials = json_decode($materials, true) ?? [];
            }
            if (is_array($materials)) {
                $existingMaterials[$translation->locale] = $materials;
            }
        }
        return $existingMaterials;
    }
}
