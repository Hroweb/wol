<?php

namespace App\Services;

use App\Models\Lesson;
use App\Repositories\LessonRepository;
use App\Traits\LocalizedServiceTrait;
use Illuminate\Support\Facades\Storage;

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
        $processedParts = $this->processParts($payload['lesson_parts'] ?? []);

        $lesson = $this->repo->createWithTranslationsAndParts($base, $translations, $processedParts);

        // Queue any large audio files for background processing
        $this->uploadService->queueLargeAudioFiles($processedParts, $lesson);

        return $lesson;
    }

    public function update(Lesson $lesson, array $payload): Lesson
    {
        $base = $this->extractBaseData($payload);
        $existingMaterials = $this->getExistingMaterials($lesson);
        $existingAudioFiles = $this->getExistingAudioFiles($lesson);
        $translations = $this->processTranslations($payload['translations'] ?? [], $existingMaterials);
        $processedParts = $this->processParts($payload['lesson_parts'] ?? [], $existingAudioFiles);

        $lesson = $this->repo->updateWithTranslationsAndParts($lesson, $base, $translations, $processedParts);

        // Queue any large audio files for background processing
        $this->uploadService->queueLargeAudioFiles($processedParts, $lesson);

        return $lesson;
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

    private function processParts(array $parts, array $existingAudioFiles = []): array
    {
        return $this->uploadService->uploadAudio($parts, $existingAudioFiles);
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

    private function getExistingAudioFiles(Lesson $lesson): array
    {
        $existingAudioFiles = [];
        foreach ($lesson->parts as $part) {
            foreach ($part->translations as $translation) {
                if ($translation->audio_file) {
                    $existingAudioFiles[$part->part_number][$translation->locale] = $translation->audio_file;
                }
            }
        }
        return $existingAudioFiles;
    }

    public function delete(Lesson $lesson): void
    {
        // Delete all lesson files (materials)
        foreach ($lesson->translations as $translation) {
            $materials = $translation->materials;
            if (is_string($materials)) {
                $materials = json_decode($materials, true) ?? [];
            }
            if (is_array($materials)) {
                foreach ($materials as $material) {
                    if (isset($material['path'])) {
                        Storage::disk('public')->delete($material['path']);
                    }
                }
            }
        }

        // Delete all audio files from lesson parts
        foreach ($lesson->parts as $part) {
            foreach ($part->translations as $translation) {
                if ($translation->audio_file) {
                    Storage::disk('public')->delete($translation->audio_file);
                }
            }
        }

        // Clean up audio upload jobs
        \App\Models\AudioUploadJob::where('lesson_id', $lesson->id)->delete();

        // Delete the lesson (cascade will handle relations)
        $lesson->delete();
    }

    public function deleteMaterial(Lesson $lesson, string $locale, int $index): bool
    {
        $translation = $lesson->translations()->where('locale', $locale)->first();

        if (!$translation) {
            return false;
        }

        $materials = $translation->materials;
        if (is_string($materials)) {
            $materials = json_decode($materials, true) ?? [];
        }
        if (!is_array($materials)) {
            $materials = [];
        }

        if (!isset($materials[$index])) {
            return false;
        }

        $materialToDelete = $materials[$index];

        // Delete the file from storage
        if (isset($materialToDelete['path'])) {
            Storage::disk('public')->delete($materialToDelete['path']);
        }

        // Remove the material from the array
        array_splice($materials, $index, 1);

        // Update the translation
        $translation->update(['materials' => json_encode($materials)]);

        return true;
    }

    public function deleteAudio(Lesson $lesson, int $partNumber, string $locale): bool
    {
        // Find the lesson part
        $lessonPart = $lesson->parts()->where('part_number', $partNumber)->first();

        if (!$lessonPart) {
            return false;
        }

        // Find the translation for this part
        $translation = $lessonPart->translations()->where('locale', $locale)->first();

        if (!$translation) {
            return false;
        }

        // Delete the audio file from storage
        if ($translation->audio_file) {
            Storage::disk('public')->delete($translation->audio_file);

            // Update the translation to remove the audio file path
            $translation->update(['audio_file' => null]);
        }

        // Clean up any related audio upload jobs (completed or failed)
        \App\Models\AudioUploadJob::where('lesson_part_id', $lessonPart->id)
            ->where('locale', $locale)
            ->whereIn('status', ['completed', 'failed'])
            ->delete();

        return true;
    }
}
