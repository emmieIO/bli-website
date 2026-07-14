<?php

namespace App\Services\Event;

use App\Enums\EventStatus;
use App\Events\Events\EventCreated;
use App\Models\Event;
use App\Traits\HasFileUpload;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class EventCrudService
{
    use HasFileUpload;

    public function createEvent(array $validated, ?UploadedFile $programCover = null): ?Event
    {
        try {
            DB::beginTransaction();
            $filepath = null;

            $validated['slug'] = (string) Str::uuid();
            unset($validated['program_cover']);

            if ($programCover) {
                $filepath = $this->uploadFile($programCover, 'program_covers');

                if (! $filepath) {
                    throw new RuntimeException('The event cover could not be stored.');
                }

                $validated['program_cover'] = $filepath;
            }

            $validated['status'] = $validated['status'] ?? EventStatus::DRAFT->value;

            $event = Event::create($validated);
            DB::commit();
            event(new EventCreated($event));

            return $event;
        } catch (\Exception $e) {
            DB::rollBack();

            if (! empty($filepath) && Storage::disk('public')->exists($filepath)) {
                Storage::disk('public')->delete($filepath);
            }

            Log::error('Event creation failed: '.$e->getMessage());

            return null;
        }
    }

    public function updateEvent(array $validated, Event $event, ?UploadedFile $programCover = null): ?Event
    {
        DB::beginTransaction();

        try {
            $filePath = $event->program_cover;
            unset($validated['program_cover']);

            if ($programCover) {
                $newFilePath = $this->uploadFile($programCover, 'program_covers');

                if (! $newFilePath) {
                    throw new RuntimeException('The replacement event cover could not be stored.');
                }

                $validated['program_cover'] = $newFilePath;

                if (! empty($filePath)) {
                    $this->removeFile($filePath);
                }
            } else {
                $validated['program_cover'] = $filePath;
            }

            $event->fill($validated);
            $event->save();
            DB::commit();

            return $event->fresh();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Event update failed: '.$e->getMessage());

            return null;
        }
    }

    public function deleteEvent(Event $event): bool
    {
        try {
            $filePath = $event->program_cover;
            $result = DB::transaction(fn () => $event->deleteOrFail());
            $this->removeFile($filePath);

            return (bool) $result;
        } catch (\Exception $e) {
            Log::error('Event deletion failed: '.$e->getMessage());

            return false;
        }
    }

    public function deleteMany(array $eventIds): bool
    {
        try {
            $events = Event::query()
                ->whereIn('id', $eventIds, 'and', false)
                ->get(['id', 'program_cover']);

            $filePaths = $events->pluck('program_cover')->filter()->toArray();

            DB::transaction(function () use ($eventIds) {
                Event::query()
                    ->whereIn('id', $eventIds, 'and', false)
                    ->delete();
            });

            foreach ($filePaths as $filePath) {
                $this->removeFile($filePath);
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Mass event deletion failed: '.$e->getMessage());

            return false;
        }
    }

    private function removeFile(?string $filePath, string $disk = 'public'): void
    {
        if (! empty($filePath) && Storage::disk($disk)->exists($filePath)) {
            Storage::disk($disk)->delete($filePath);
        }
    }
}
