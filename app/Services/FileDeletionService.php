<?php

namespace App\Services;

use App\Models\Lesson;
use App\Models\LessonPart;
use App\Models\LessonTranslation;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class FileDeletionService
{
    public function deleteMaterialFile(LessonTranslation $translation, int $index): bool
    {
        $materials = $this->getMaterialsArray($translation->materials);

        if (!isset($materials[$index])) {
            return false;
        }

        $materialToDelete = $materials[$index];

        // Delete the file from storage
        if (isset($materialToDelete['path'])) {
            $this->deleteFileFromStorage($materialToDelete['path']);
        }

        // Remove the material from the array
        array_splice($materials, $index, 1);

        // Update the translation
        $translation->update(['materials' => json_encode($materials)]);

        return true;
    }

    public function deleteAllLessonFiles(Lesson $lesson): void
    {
        foreach ($lesson->translations as $translation) {
            $this->deleteAllTranslationFiles($translation);
        }
    }

    public function deleteAllTranslationFiles(LessonTranslation $translation): void
    {
        $materials = $this->getMaterialsArray($translation->materials);

        foreach ($materials as $material) {
            if (isset($material['path'])) {
                $this->deleteFileFromStorage($material['path']);
            }
        }

        // Clear materials from database
        $translation->update(['materials' => json_encode([])]);
    }

    private function getMaterialsArray($materials): array
    {
        if (is_string($materials)) {
            return json_decode($materials, true) ?? [];
        }

        return is_array($materials) ? $materials : [];
    }

    public function deleteAudioFiles(Lesson $lesson): void
    {
        foreach ($lesson->parts as $part) {
            foreach ($part->translations as $translation) {
                if ($translation->audio_file) {
                    $this->deleteFileFromStorage($translation->audio_file);
                }
            }
        }

        // Clean up all related audio upload jobs for this lesson
        \App\Models\AudioUploadJob::where('lesson_id', $lesson->id)->delete();
    }

    public function deletePartAudioFiles(\App\Models\LessonPart $part): void
    {
        foreach ($part->translations as $translation) {
            if ($translation->audio_file) {
                $this->deleteFileFromStorage($translation->audio_file);
            }
        }

        // Clean up related audio upload jobs for this part
        \App\Models\AudioUploadJob::where('lesson_part_id', $part->id)->delete();
    }

    public function deleteAudioFile(Lesson $lesson, int $partNumber, string $locale): bool
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
            $this->deleteFileFromStorage($translation->audio_file);

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

    public function deleteFileFromStorage(string $path): void
    {
        try {
            Storage::disk('public')->delete($path);
            return;
        } catch (\Exception $e) {
            // Log the error but don't throw to prevent breaking the flow
            Log::error("Failed to delete file: {$path}. Error: " . $e->getMessage());
            return;
        }
    }
}
