<?php

namespace App\Services;

use App\Models\Lesson;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileUploadService
{
    public function __construct(
        private readonly AudioQueueService $audioQueueService
    ) {}

    public function uploadMaterials(array $translations, array $existingMaterials = []): array
    {
        foreach ($translations as $locale => $translation) {
            if (isset($translation['materials']) && is_array($translation['materials'])) {
                $uploadedFiles = [];
                foreach ($translation['materials'] as $file) {
                    if ($file && $file->isValid()) {
                        $uploadedFiles[] = $this->processFile($file, 'lessons/materials');
                    }
                }
                $translations[$locale]['materials'] = $uploadedFiles;
            } else {
                // Preserve existing materials if no new files are uploaded
                if (isset($existingMaterials[$locale])) {
                    $translations[$locale]['materials'] = $existingMaterials[$locale];
                }
            }
        }
        return $translations;
    }

    public function uploadAudio(array $parts, array $existingAudioFiles = []): array
    {
        foreach ($parts as $index => $part) {
            $partNumber = $part['part_number'];

            if (isset($part['translations'])) {
                foreach ($part['translations'] as $locale => $translation) {
                    // Check if new audio file is uploaded
                    if (isset($translation['audio_file']) && $translation['audio_file'] && $translation['audio_file']->isValid()) {
                        $file = $translation['audio_file'];

                        // Check file size to determine if we should queue it
                        $fileSizeMB = $file->getSize() / (1024 * 1024);

                        // For large files (> 10MB), store temporarily for queue processing
                        if ($fileSizeMB > 10) {
                            // Store in temp location, will be moved by job
                            $tempPath = $file->store('temp/audio', 'local');
                            $parts[$index]['translations'][$locale]['_temp_path'] = $tempPath;
                            $parts[$index]['translations'][$locale]['_file_size'] = $file->getSize();
                            $parts[$index]['translations'][$locale]['_original_name'] = $file->getClientOriginalName();
                            $parts[$index]['translations'][$locale]['audio_file'] = null; // Will be set by job
                        } else {
                            // Upload small files immediately
                            $path = $file->store("lessons/audio/{$locale}", 'public');
                            $parts[$index]['translations'][$locale]['audio_file'] = $path;
                        }
                    } else {
                        // Check if there's an existing_audio_file from form (hidden input)
                        if (isset($translation['existing_audio_file']) && !empty($translation['existing_audio_file'])) {
                            $parts[$index]['translations'][$locale]['audio_file'] = $translation['existing_audio_file'];
                        }
                        // Otherwise, check the passed existingAudioFiles array (from database)
                        elseif (isset($existingAudioFiles[$partNumber][$locale])) {
                            $parts[$index]['translations'][$locale]['audio_file'] = $existingAudioFiles[$partNumber][$locale];
                        } else {
                            // No new file and no existing file, set to null
                            $parts[$index]['translations'][$locale]['audio_file'] = null;
                        }
                    }
                }
            }
        }
        return $parts;
    }

    /**
     * Queue audio files that were temporarily stored
     */
    public function queueLargeAudioFiles(array $processedParts, Lesson $lesson): void
    {
        foreach ($processedParts as $partData) {
            $partNumber = $partData['part_number'];

            // Find the corresponding LessonPart model
            $lessonPart = $lesson->parts()->where('part_number', $partNumber)->first();

            if (!$lessonPart || !isset($partData['translations'])) {
                continue;
            }

            foreach ($partData['translations'] as $locale => $translationData) {
                // Check if this file was temporarily stored for queue processing
                if (isset($translationData['_temp_path'])) {
                    $this->audioQueueService->queueAudioUpload(
                        tempPath: $translationData['_temp_path'],
                        originalFilename: $translationData['_original_name'] ?? 'audio.mp3',
                        fileSize: $translationData['_file_size'] ?? 0,
                        lesson: $lesson,
                        lessonPart: $lessonPart,
                        locale: $locale
                    );
                }
            }
        }
    }

    private function processFile(UploadedFile $file, string $directory): array
    {
        $path = $file->store($directory, 'public');
        return [
            'name' => $file->getClientOriginalName(),
            'path' => $path,
            'size' => $file->getSize(),
            'mime_type' => $file->getMimeType()
        ];
    }
}
