<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileUploadService
{
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

    public function uploadAudio(array $parts): array
    {
        foreach ($parts as $index => $part) {
            if (isset($part['translations'])) {
                foreach ($part['translations'] as $locale => $translation) {
                    if (isset($translation['audio_file']) && $translation['audio_file'] && $translation['audio_file']->isValid()) {
                        $file = $translation['audio_file'];
                        $path = $file->store("lessons/audio/{$locale}", 'public');
                        $parts[$index]['translations'][$locale]['audio_file'] = $path;
                    }
                    // Remove the file from the array as it's not needed in the database
                    unset($parts[$index]['translations'][$locale]['audio_file']);
                }
            }
        }
        return $parts;
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
