<?php

namespace App\Services;

use App\Models\Lesson;
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

    private function deleteFileFromStorage(string $path): void
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
