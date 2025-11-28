<?php

namespace App\Console\Commands;

use App\Models\Lesson;
use App\Services\VimeoService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateVimeoVideoStatus extends Command
{
    protected $signature = 'vimeo:update-status {--lesson_id=}';
    protected $description = 'Update the processing status of Vimeo videos for lessons';

    public function handle(VimeoService $vimeoService)
    {
        $this->info('Checking Vimeo video statuses...');

        // Get lessons with videos that are still processing
        $query = Lesson::where('type', 'video')
            ->whereNotNull('vimeo_id')
            ->whereIn('video_status', ['pending', 'uploading', 'processing']);

        // If specific lesson ID is provided
        if ($lessonId = $this->option('lesson_id')) {
            $query->where('id', $lessonId);
        }

        $lessons = $query->get();

        if ($lessons->isEmpty()) {
            $this->info('No lessons with pending/processing videos found.');
            return 0;
        }

        $this->info("Found {$lessons->count()} lesson(s) to check.");

        $updated = 0;
        $failed = 0;

        foreach ($lessons as $lesson) {
            $this->line("Checking lesson: {$lesson->title} (ID: {$lesson->id})");

            try {
                $statusCheck = $vimeoService->getVideoStatus($lesson->vimeo_id);

                $oldStatus = $lesson->video_status;
                $newStatus = $statusCheck['status'];

                if ($statusCheck['is_ready']) {
                    $lesson->video_status = 'ready';
                    $lesson->video_error = null;
                    $lesson->save();

                    $this->info("  ✅ Status updated: {$oldStatus} → ready");
                    $updated++;
                } elseif ($newStatus === 'error' || $newStatus === 'failed') {
                    $lesson->video_status = 'failed';
                    $lesson->video_error = 'Video processing failed on Vimeo';
                    $lesson->save();

                    $this->error("  ❌ Video processing failed");
                    $failed++;
                } else {
                    $this->line("  ⏳ Still processing (status: {$newStatus})");
                }

            } catch (\Exception $e) {
                $this->error("  ❌ Error checking status: {$e->getMessage()}");
                Log::error("Failed to check Vimeo status for lesson {$lesson->id}", [
                    'error' => $e->getMessage(),
                    'vimeo_id' => $lesson->vimeo_id,
                ]);
            }
        }

        $this->newLine();
        $this->info("Summary:");
        $this->info("  • Updated to ready: {$updated}");
        $this->info("  • Failed: {$failed}");
        $this->info("  • Still processing: " . ($lessons->count() - $updated - $failed));

        return 0;
    }
}
