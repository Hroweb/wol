<?php

namespace App\Services;

use App\Jobs\ProcessAudioUpload;
use App\Models\AudioUploadJob;
use App\Models\Lesson;
use App\Models\LessonPart;

class AudioQueueService
{
    /**
     * Queue an audio file for processing
     */
    public function queueAudioUpload(
        string $tempPath,
        string $originalFilename,
        int $fileSize,
        Lesson $lesson,
        ?LessonPart $lessonPart,
        string $locale
    ): AudioUploadJob {
        // Create tracking record
        $audioUploadJob = AudioUploadJob::create([
            'lesson_id' => $lesson->id,
            'lesson_part_id' => $lessonPart?->id,
            'locale' => $locale,
            'original_filename' => $originalFilename,
            'temp_path' => $tempPath,
            'status' => 'pending',
            'file_size' => $fileSize,
        ]);

        // Dispatch the job to process the audio
        ProcessAudioUpload::dispatch($audioUploadJob->id);

        return $audioUploadJob;
    }

    /**
     * Get upload status for a lesson
     */
    public function getUploadStatus(Lesson $lesson): array
    {
        $jobs = AudioUploadJob::where('lesson_id', $lesson->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return [
            'total' => $jobs->count(),
            'pending' => $jobs->where('status', 'pending')->count(),
            'processing' => $jobs->where('status', 'processing')->count(),
            'completed' => $jobs->where('status', 'completed')->count(),
            'failed' => $jobs->where('status', 'failed')->count(),
            'jobs' => $jobs,
        ];
    }

    /**
     * Check if lesson has any pending uploads
     */
    public function hasPendingUploads(Lesson $lesson): bool
    {
        return AudioUploadJob::where('lesson_id', $lesson->id)
            ->whereIn('status', ['pending', 'processing'])
            ->exists();
    }

    /**
     * Clean up old completed jobs
     */
    public function cleanupOldJobs(int $daysOld = 7): int
    {
        $date = now()->subDays($daysOld);

        return AudioUploadJob::where('status', 'completed')
            ->where('created_at', '<', $date)
            ->delete();
    }
}
