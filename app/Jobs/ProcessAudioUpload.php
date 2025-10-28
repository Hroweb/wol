<?php

namespace App\Jobs;

use App\Models\AudioUploadJob;
use App\Models\LessonPartTranslation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProcessAudioUpload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 600; // 10 minutes for large files
    public int $maxExceptions = 3;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public int $audioUploadJobId
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $audioUploadJob = AudioUploadJob::findOrFail($this->audioUploadJobId);

        try {
            // Mark as processing
            $audioUploadJob->markAsProcessing();

            // Verify temp file exists
            if (!Storage::disk('local')->exists($audioUploadJob->temp_path)) {
                throw new \Exception("Temporary file not found: {$audioUploadJob->temp_path}");
            }

            // Move file from temp to final location
            $finalPath = "lessons/audio/{$audioUploadJob->lesson_id}/{$audioUploadJob->locale}/" . basename($audioUploadJob->temp_path);

            // Copy from local temp storage to public storage
            $tempFileContents = Storage::disk('local')->get($audioUploadJob->temp_path);
            Storage::disk('public')->put($finalPath, $tempFileContents);

            // Delete temp file
            Storage::disk('local')->delete($audioUploadJob->temp_path);

            // Update the lesson part translation with the final path
            if ($audioUploadJob->lesson_part_id) {
                $translation = LessonPartTranslation::where('lesson_part_id', $audioUploadJob->lesson_part_id)
                    ->where('locale', $audioUploadJob->locale)
                    ->first();

                if ($translation) {
                    $translation->update(['audio_file' => $finalPath]);
                }
            }

            // Mark as completed
            $audioUploadJob->markAsCompleted($finalPath);

            Log::info("Audio upload completed successfully", [
                'job_id' => $audioUploadJob->id,
                'lesson_id' => $audioUploadJob->lesson_id,
                'final_path' => $finalPath,
            ]);

        } catch (\Exception $e) {
            // Mark as failed
            $audioUploadJob->markAsFailed($e->getMessage());

            Log::error("Audio upload failed", [
                'job_id' => $audioUploadJob->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Clean up temp file if it exists
            if (Storage::disk('local')->exists($audioUploadJob->temp_path)) {
                Storage::disk('local')->delete($audioUploadJob->temp_path);
            }

            // Re-throw to mark job as failed
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        $audioUploadJob = AudioUploadJob::find($this->audioUploadJobId);

        if ($audioUploadJob) {
            $audioUploadJob->markAsFailed($exception->getMessage());
        }

        Log::error("Audio upload job failed permanently", [
            'job_id' => $this->audioUploadJobId,
            'error' => $exception->getMessage(),
        ]);
    }
}

